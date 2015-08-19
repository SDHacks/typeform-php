<?php

namespace Typeform\Test;

use Typeform\Form;
use Typeform\Typeform;

class ExampleTest extends \PHPUnit_Framework_TestCase
{

    protected $apiKey;
    protected $formId;

    /** @var  Typeform */
    protected $client;

    /** @var  Form */
    protected $form;

    protected function setUp()
    {
        $this->apiKey = getenv('TYPEFORM_KEY');
        $this->formId = getenv('TYPEFORM_FORM_ID');
        $this->client = new Typeform($this->apiKey);
        $this->form = $this->client->getForm($this->formId);
    }

    public function testCreateTypeform()
    {
        $this->assertInstanceOf('Typeform\Typeform', $this->client);
    }

    public function testGetForm()
    {
        $this->assertInstanceOf('Typeform\Form', $this->form);
    }

    public function testGetQuestions()
    {
        $this->assertNotNull($this->form->getQuestions());
    }

    public function testGetResponses()
    {
        $this->assertNotNull($this->form->getResponses());
    }

    public function testNextPage()
    {
        $currentPage = $this->form->getPage();
        $this->form->nextPage();
        $this->assertEquals($currentPage + 1, $this->form->getPage());

        // Reset
        $this->form->setPage($currentPage);
    }

    public function testPrevPage()
    {
        $currentPage = $this->form->getPage();
        $this->form->setPage($currentPage + 1);
        $this->form->prevPage();

        $this->assertEquals($currentPage, $this->form->getPage());
    }

    public function testGetNextPage()
    {
        $currentPage = $this->form->getPage();
        $this->form->getNextPage();
        $this->assertEquals($currentPage + 1, $this->form->getPage());
    }

    public function testGetPrevPage()
    {
        $currentPage = $this->form->getPage();
        $this->form->setPage($currentPage + 1);
        $this->form->getPrevPage();
        $this->assertEquals($currentPage, $this->form->getPage());
    }
}
