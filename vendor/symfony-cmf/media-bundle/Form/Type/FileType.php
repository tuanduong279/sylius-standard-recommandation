<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\MediaBundle\Form\Type;

use Symfony\Cmf\Bundle\MediaBundle\File\UploadFileHelperInterface;
use Symfony\Cmf\Bundle\MediaBundle\Form\DataTransformer\ModelToFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type which transforms an uploaded file to an object implementing the
 * Symfony\Cmf\Bundle\MediaBundle\FileInterface.
 *
 * It renders as a file upload button with a link for downloading the existing
 * file, if any.
 *
 * Usage: you need to supply the object class to which the file will be
 * transformed (which should implement FileInterface) and an UploadFileHelper,
 * which will handle the UploadedFile and create the transformed object.
 */
class FileType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var UploadFileHelperInterface
     */
    protected $uploadFileHelper;

    /**
     * @param string                    $class
     * @param UploadFileHelperInterface $uploadFileHelper
     */
    public function __construct($class, UploadFileHelperInterface $uploadFileHelper)
    {
        $this->dataClass = $class;
        $this->uploadFileHelper = $uploadFileHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cmf_media_file';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ModelToFileTransformer($this->uploadFileHelper, $options['data_class']);
        $builder->addModelTransformer($transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $options)
    {
        $this->configureOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $options)
    {
        $options->setDefaults(array('data_class' => $this->dataClass));
    }
}
