<?php
class Contact_IndexController extends Zend_Controller_Action
{
    public function indexAction ()
    {
        $frmContact = new Contact_Form_Contact();
        if ($this->_request->isPost() && $frmContact->isValid($_POST)) {
            // get the posted data
            $sender = $frmContact->getValue('name');
            $email = $frmContact->getValue('email');
            $subject = $frmContact->getValue('subject');
            $message = $frmContact->getValue('message');
            // load the template
            $htmlMessage = $this->view->partial('templates/default.phtml', $frmContact->getValues());
            
            $mail = new Zend_Mail();
            // configure and create the SMTP connection
            $config = array('auth' => 'login',
                'username' => 'myusername',
                'password' => 'password');

            $transport = new Zend_Mail_Transport_Smtp('mail.server.com', $config);

            // set the subject
            $mail->setSubject($subject);
            // set the message's from address to the person who submitted the form
            $mail->setFrom($email, $sender);
            // for the sake of this example you can hardcode the recipient
            $mail->addTo('forrestlyman@gmail.com', 'webmaster');
            // add the file attachment
            $fileControl = $frmContact->getElement('attachment');
            if($fileControl->isUploaded()) {
                $attachmentName = $fileControl->getFileName();
                $fileStream = file_get_contents($attachmentName);
                // create the attachment
                $attachment = $mail->createAttachment($fileStream);
                $attachment->filename = basename($attachmentName);
            }
            // it is important to provide a text only version in addition to the html message
            $mail->setBodyHtml($htmlMessage);
            $mail->setBodyText($message);
            //send the message, now using SMTP transport
            $result = $mail->send($transport);
            
            // inform the view with the status
            $this->view->messageProcessed = true;
            if ($result) {
                $this->view->sendError = false;
            } else {
                $this->view->sendError = true;
            }
        }
        $frmContact->setAction('/contact');
        $frmContact->setMethod('post');
        $this->view->form = $frmContact;
    }
}
?>


