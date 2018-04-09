<?php
/**
 * Class XmlResponse | XmlResponse.php
 * @package Faulancer\Http
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Http;

/**
 * Class XmlResponse
 */
class XmlResponse extends Response
{

    /**
     * XmlResponse constructor.
     *
     * @param array $content
     */
    public function __construct ($content = [])
    {
        parent::__construct($content);
        $this->setContent($content);
    }

    /** @var array  */
    protected $content = [];

    /**
     * @param $dom
     * @param $data
     * @return bool|\DOMElement
     */
    private function generateXmlElement(\DOMDocument $dom, $data )
    {
        if (empty($data['name'])) {
            return false;
        }

        // Create the element
        $elementValue = (!empty($data['value'])) ? $data['value'] : null;
        $element = $dom->createElement($data['name'], $elementValue);

        // Add any attributes
        if (!empty($data['attributes']) && is_array($data['attributes'])) {

            foreach ($data['attributes'] as $attributeKey => $attributeValue) {
                $element->setAttribute($attributeKey, $attributeValue);
            }

        }

        // Any other items in the data array should be child elements
        foreach ($data as $data_key => $childData) {

            if (!is_numeric($data_key)) {
                continue;
            }

            $child = $this->generateXmlElement($dom, $childData);

            if ($child) {
                $element->appendChild($child);
            }

        }

        return $element;
    }

    /**
     * @param array $content
     * @return self
     */
    public function setContent($content = [])
    {
        $this->setResponseHeader(['Content-Type' => 'text/xml']);

        $doc = new \DOMDocument();
        $doc->xmlVersion = '1.0';
        $doc->encoding   = 'UTF-8';
        $child = $this->generateXmlElement($doc, $content);

        if ( $child ) {
            $doc->appendChild($child);
        }

        $doc->formatOutput = true; // Add line breaks to make it easier to read
        $xml = $doc->saveXML();

        $this->content = trim($xml);
        return $this;
    }

}