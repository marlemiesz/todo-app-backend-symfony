<?php

namespace App\Serializer\Normalizer;

use App\Entity\TaskList;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TaskListNormalizer implements NormalizerInterface {
    /**
     * @var Packages
     */
    private $packages;
    /**
     * @var ObjectNormalizer
     */
    private $objectNormalizer;

    /**
     * TaskListNormalizer constructor.
     * @param ObjectNormalizer $objectNormalizer
     * @param Packages $packages
     */
    public function __construct(ObjectNormalizer $objectNormalizer, Packages $packages)
    {

        $this->packages = $packages;
        $this->objectNormalizer = $objectNormalizer;
    }

    /**
     * @param mixed $object
     * @param null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|void|null
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $object->setBackgroundPath(
            $this->packages->getUrl($object->getBackgroundPath(), 'backgrounds')
        );

        $data = $this->objectNormalizer->normalize($object, $format, $context);

        return $data;
    }

    /**
     * @param mixed $data
     * @param null $format
     * @return bool|void
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof TaskList;
    }
}