<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleBodyCommand;

/**
 * Abstract class to create or modify an Unfuddle message
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
abstract class AbstractMessageBodyCommand extends AbstractUnfuddleBodyCommand
{
    /**
     * {@inheritdoc}
     */
    protected $containingElement = 'message';

    /**
     * Set the message body
     *
     * @param string $body Message body
     * 
     * @return AbstractMessageBodyCommand
     */
    public function setBody($body)
    {
        return $this->setXmlValue('body', $body);
    }

    /**
     * Set the message title
     *
     * @param string $title Message title
     * 
     * @return AbstractMessageBodyCommand
     */
    public function setTitle($title)
    {
        return $this->setXmlValue('title', $title);
    }

    /**
     * Set the message categories
     *
     * @param array|string $categories Categories
     * 
     * @return AbstractMessageBodyCommand
     */
    public function setCategories($categories)
    {
        $this->setXmlValue('categories', '');
        $cats = $this->getXmlBody()->categories;
        foreach ((array)$categories as $category) {
            $node = $cats->addChild('category', '');
            $node->addAttribute('id', $category);
        }
    }
}