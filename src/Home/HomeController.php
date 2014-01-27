<?php

namespace Home;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $connection = Connector::getActiveConnection();

        $query = sprintf('SELECT * FROM marysh_lessons WHERE marysh_lessons.published = 1');
        $lessons = $connection->select($query);

        return $this->render('index.html.twig', array('lessons' => $lessons));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function menuBlockAction(Request $request)
    {
        $connection = Connector::getActiveConnection();

        $query = sprintf('SELECT * FROM marysh_lessons WHERE marysh_lessons.published = 1');
        $lessons = $connection->select($query);

        $lessons = array();
        return $this->render('menu.html.twig', array('lessons' => $lessons));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sceenPageAction(Request $request)
    {
        return $this->render('sceen.html.twig', array(
            'page_title' => 'Loading!'
        ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutPageAction(Request $request)
    {
        return $this->render('about.html.twig', array(
            'page_title' => 'About!'
        ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mailPageAction(Request $request)
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
            }
        }

        return $this->render('mail.html.twig', array(
            'page_title' => 'Email us!',
            'errors' => $errors,
            'data' => $data,
        ));
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
            }
        }

        return $this->render('mail_block.html.twig', array(
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

        $fromEmail = $data['email'] ? $data['email'] : 'prikritie@gmail.com';

        $message = \Swift_Message::newInstance()
            ->setSubject('Rats records email')
            ->setFrom(array($fromEmail => $data['name']))
            ->addTo('varloc2000@gmail.com')
            ->setBody($data['feedback'])
        ;

        return $mailer->send($message);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactPageAction(Request $request)
    {
        return $this->render('contacts.html.twig', array(
            'page_title' => 'We are here!'
        ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function galleryPageAction(Request $request)
    {
        return $this->render('gallery.html.twig', array(
            'page_title' => 'Photos!'
        ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function galleryBlockAction(Request $request)
    {
        return $this->render('block_gallery.html.twig', array(
            'page_title' => 'Photos!'
        ));
    }
}
