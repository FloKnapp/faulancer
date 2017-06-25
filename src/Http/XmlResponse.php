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
     * @param array             $data
     * @param \SimpleXMLElement $xml
     * @codeCoverageIgnore
     */
    private function convertArrayToXml(\SimpleXMLElement &$xml, $data)
    {
        foreach ($data as $key=>$value) {

            if(is_numeric($key)) {
                $key = 'item' . $key;
            }

            if(!empty($value['value']) && is_array($value['value'])) {

                $node = $this->generateNode($xml, $key, $value);
                $this->convertArrayToXml($node, $value['value']);

            } else {
                $this->generateNode($xml, $key, $value);
            }

        }
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param string            $key
     * @param null|array|string $value
     * @return null|\SimpleXMLElement
     * @codeCoverageIgnore
     */
    private function generateNode(\SimpleXMLElement &$xml, $key, $value = null)
    {
        $node = null;

        if (in_array('@attributes', array_keys($value))) {

            $attributes = $value['@attributes'];

            if (!empty($value['value']) && !is_array($value['value'])) {

                $node = $xml->addChild($key, htmlspecialchars($value));

                foreach ($attributes as $attr => $val) {
                    $node->addAttribute($attr, $val);
                }

            } else {

                $node = $this->convertArrayToXml($xml, $value);

            }

        } else if (!empty($value['value']) && is_array($value['value'])) {

            $node = $this->convertArrayToXml($xml, $value['value']);

        } else {

            $node = $xml->addChild($key, htmlspecialchars($value));

        }



        return $node;
    }

    /**
     * @param array $content
     * @return self
     * @codeCoverageIgnore
     */
    public function setContent($content = [])
    {
        $this->setResponseHeader(['Content-Type' => 'text/xml']);

        $xml = new \SimpleXMLElement('<?xml version="1.0"?><root></root>');
        $this->convertArrayToXml($xml, $content);
        $result = $xml->asXml();

        $this->content = $result;
        return $this;
    }

}