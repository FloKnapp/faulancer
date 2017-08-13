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
    protected function generateXmlElement( \DOMDocument $dom, $data )
    {
        if ( empty( $data['name'] ) )
            return false;

        // Create the element
        $element_value = ( ! empty( $data['value'] ) ) ? $data['value'] : null;
        $element = $dom->createElement( $data['name'], $element_value );

        // Add any attributes
        if ( ! empty( $data['attributes'] ) && is_array( $data['attributes'] ) ) {
            foreach ( $data['attributes'] as $attribute_key => $attribute_value ) {
                $element->setAttribute( $attribute_key, $attribute_value );
            }
        }

        // Any other items in the data array should be child elements
        foreach ( $data as $data_key => $child_data ) {
            if ( ! is_numeric( $data_key ) )
                continue;

            $child = $this->generateXmlElement( $dom, $child_data );
            if ( $child )
                $element->appendChild( $child );
        }

        return $element;
    }

    /**
     * @param array $content
     * @return self
     * @codeCoverageIgnore
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

        $doc->formatOutput = true; // Add whitespace to make easier to read XML
        $xml = $doc->saveXML();

        $result = $xml;

        $this->content = $result;
        return $this;
    }

}