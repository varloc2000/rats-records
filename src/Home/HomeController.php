<?php

namespace Home;

use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('layout.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function mainPageContentAction(Request $request)
    {
        // $request->getSession()->getFlashBag()->add(
        //     'rr.red.success',
        //     'rr.flash.mail_form.success'
        // );
        // $request->getSession()->getFlashBag()->add(
        //     'rr.yellow.error',
        //     'rr.flash.mail_form.error'
        // );
        // $request->getSession()->getFlashBag()->add(
        //     'rr.orange.error',
        //     'rr.flash.mail_form.error'
        // );
        // $request->getSession()->getFlashBag()->add(
        //     'rr.orange.error',
        //     'rr.flash.mail_form.asdfsadf'
        // );

        return $this->render('index.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function galleryBlockAction(Request $request)
    {
        return $this->render('_block_gallery.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recordsBlockAction(Request $request)
    {
        return $this->render('_block_records.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutBlockAction(Request $request)
    {
        return $this->render('_block_about.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function playlistBlockAction(Request $request)
    {
        return $this->render('_block_playlist.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailBlockAction(Request $request)
    {
        $errors = array();
        $data = array();
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

        if ('POST' == $request->getMethod()) {
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
                $this->sendEmailToMe($data);

                $request->getSession()->getFlashBag()->add(
                    'rr.yellow.success',
                    'rr.flash.mail_form.success'
                );
            } else {
                $request->getSession()->getFlashBag()->add(
                    'rr.yellow.error',
                    'rr.flash.mail_form.error'
                );
            }
        }

        return $this->render('_block_mail.html.twig', array(
            'page_title' => 'Email us!',
            'errors' => $errors,
            'data' => $data,
        ));
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

        $fromEmail = isset($data['email']) ? $data['email'] : 'prikritie@gmail.com';

        $message = \Swift_Message::newInstance()
            ->setSubject('Rats records email')
            ->setFrom(array($fromEmail => $data['name']))
            ->addTo('varloc2000@gmail.com')
            ->setBody($data['feedback'])
        ;

        return $mailer->send($message);
    }
}
