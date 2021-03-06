<?php
namespace DJBlaster\BlasterBundle\Form\DataTransformer;

use \DateTime;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeToStringTransformer implements DataTransformerInterface
{
    /**
     * Transforms a datetime object to a string. 
     *
     * @return string
     */
    public function transform($dateTime)
    {

        if (!is_object($dateTime)) {
            $hour = date('H');
            $minute = (date('i') > 30) ? '30' : '00';
            $ampm = date('a');
            return "$hour:$minute $ampm";
        }

        return $dateTime->format('g:ia');
    }

    /**
     * Transforms a time string to a DateTime object
     *
     * @param  string $string
     *
     * @return array
     */
    public function reverseTransform($timeString)
    {

        $time = new DateTime();
        //$time->createFromFormat('g:ia', $timeString);
        $time->setTimestamp(strtotime($timeString));

        return $time;
    }
}