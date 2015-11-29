<?php

namespace Home;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

use Varloc\Framework\Controller\Controller as FrameworkController;
use Varloc\Framework\Database\Connector;

class HomeController extends FrameworkController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mainPageAction(Request $request)
    {
        return $this->render('layout_ajax.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mainPageContentAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->render('index.html.twig');
        } else {
            return $this->render('layout.html.twig');
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Routing\Exception\MethodNotAllowedException
     */
    public function mailAjaxAction(Request $request)
    {
        $errors = $data = $responseData = array();
        $validation = array(
            'email' => array(
                'required' => false,
                'isValid' => function($string) {
                    $valid = true;

                    if (strlen($string) > 0) {
                        $pattern = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                        if (strlen($string) < 5) {
                            $valid = 'email_limit_min_error';
                        } else if (strlen($string) > 100) {
                            $valid = 'email_limit_max_error';
                        } else if (!preg_match($pattern, $string)) {
                            $valid = 'email_format_error';
                        }
                    }

                    return $valid;
                },
            ),
            'name' => array(
                'required' => true,
                'isValid' => function($string) {
                    $valid = true;
                    if (strlen($string) < 2) {
                        $valid = 'name_limit_min_error';
                    } else if (strlen($string) > 100) {
                        $valid = 'name_limit_max_error';
                    }

                    return $valid;
                },
            ),
            'feedback' => array(
                'required' => true,
                'isValid' => function($string) {
                    $valid = true;
                    if (strlen($string) < 5) {
                        $valid = 'feedback_limit_min_error';
                    } else if (strlen($string) > 6000) {
                        $valid = 'feedback_limit_max_error';
                    }

                    return $valid;
                }
            ),
        );

        if (!$request->isMethod('POST') || !$request->isXmlHttpRequest()) {
            // Force redirect back
            return new RedirectResponse('/');
        }

        foreach ($request->request->all() as $fieldName => $value) {
            if (!array_key_exists($fieldName, $validation)) {
                $errors['form'][] = sprintf('Unrecognized extra field is submitted "%s"', $fieldName);

            } else if (empty($value) && true === $validation[$fieldName]['required']) {
                $errors[$fieldName][] = 'required_field';

            } else if (true !== ($message = call_user_func($validation[$fieldName]['isValid'], $value))) {
                $errors[$fieldName][] = $message;
            }

            $data[$fieldName] = $value;
        }

        if (empty($errors)) {
            try {
                $this->sendEmailToMe($data);
            } catch (\Exception $e) {
                // Do nothing
            }

            $responseData = array(
                'success' => true,
                'flasher_section' => 'fifth',
                'flasher_message' => 'rr.flash.mail_form.success',
            );
        } else {
            $responseData = array(
                'success' => false,
                'flasher_section' => 'fifth',
                'flasher_message' => 'rr.flash.mail_form.error',
                'errors' => $errors,
            );
        }

        return new JsonResponse($responseData);
    }

    /**
     * sendEmailToMe send email to varloc2000@gmail.com
     * @param array $data
     * @return integer
     */
    private function sendEmailToMe($data)
    {
        $transport = \Swift_MailTransport::newInstance();
        $mailer = \Swift_Mailer::newInstance($transport);

        $email = $data['email'];
        $fromEmail = empty($email) ? 'prikritie@gmail.com' : $email;

        $message = \Swift_Message::newInstance()
            ->setSubject('Rats records email')
            ->setFrom(array($fromEmail => $data['name']))
            ->addTo('varloc2000@gmail.com')
            ->setBody($data['feedback'])
        ;

        return $mailer->send($message);
    }
}
