<?php

namespace App\Serializer\Normalizer;

use App\Entity\Note;
use App\Entity\TaskList;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PublicDataNormalizer implements NormalizerInterface {
    /**
     * @var ObjectNormalizer
     */
    private $objectNormalizer;

    /**
     * PublicDataNormalizer constructor.
     * @param ObjectNormalizer $objectNormalizer
     */
    public function __construct(ObjectNormalizer $objectNormalizer)
    {
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

        $context['ignored_attributes'] = ['user'];

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
        return $data instanceof TaskList || $data instanceof Task || $data instanceof Note;
    }
}