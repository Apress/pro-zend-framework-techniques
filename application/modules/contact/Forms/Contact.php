<?php
class Contact_Form_Contact extends Zend_Form 
{
    public function init()
    {
        // create new element
        $name = $this->createElement('text', 'name');
        // element options
        $name->setLabel('Enter your name:');
        $name->setRequired(TRUE);
        $name->setAttrib('size',40);
        // add the element to the form
        $this->addElement($name);
        
        // create new element
        $email = $this->createElement('text', 'email');
        // element options
        $email->setLabel('Enter your email address:');
        $email->setRequired(TRUE);    
        $email->setAttrib('size',40);
        $email->addValidator('EmailAddress');
        $email->addErrorMessage('Invalid email address!');
        // add the element to the form
        $this->addElement($email);
        
        // create new element
        $subject = $this->createElement('text', 'subject');
        // element options
        $subject->setLabel('Subject: ');
        $subject->setRequired(TRUE);
        $subject->setAttrib('size',60);
        // add the element to the form
        $this->addElement($subject);
        
        // create new element
        $attachment = $this->createElement('file', 'attachment');
        // element options
        $attachment->setLabel('Attach a file');
        $attachment->setRequired(FALSE);
        // specify the path to the upload folder. this should not be publicly accessible!
        $attachment->setDestination(APPLICATION_PATH . '/../uploads');
        // ensure that only 1 file is uploaded
        $attachment->addValidator('Count', false, 1);
        // limit to 100K
        $attachment->addValidator('Size', false, 102400);
        // only allow images to be uploaded
        $attachment->addValidator('Extension', false, 'jpg,png,gif');
        // add the element to the form
        //$this->addElement($attachment);
        // set the enctype attribute for the form so it can upload files
        $this->setAttrib('enctype', 'multipart/form-data');
        
        // create new element
        $message = $this->createElement('textarea', 'message');
        // element options
        $message->setLabel('Message:');
        $message->setRequired(TRUE);
        $message->setAttrib('cols',50);
        $message->setAttrib('rows',12);
        // add the element to the form
        $this->addElement($message);
        
        // configure the captcha service
        $privateKey = '6Lf-LwcAAAAAAMSOrrjbogfM6ytHs0u3oLI3Zuv0';
        $publicKey = '6Lf-LwcAAAAAAO9aLI2lhXdcEe6l5PSAtKOo7k4K';
        $recaptcha = new Zend_Service_ReCaptcha($publicKey, $privateKey);
        
        // create the captcha control
        $captcha = new Zend_Form_Element_Captcha('captcha',
            array('captcha'        => 'ReCaptcha',
    		'captchaOptions' => array('captcha' => 'ReCaptcha', 'service' => $recaptcha)));
        
        // add captcha to the form
        //$this->addElement($captcha);
            
        
        $submit = $this->addElement('submit', 'submit', array('label' => 'Send Message'));        
    }
}
?>