<?php

namespace Symfony\Bundle\AsseticBundle\Templating;

use Symfony\Component\Templating\TemplateReference as BaseTemplateReference;

class TemplateReference extends BaseTemplateReference
{
    public function __construct(string $bundle = null, string $controller = null, string $name = null, string $format = null, string $engine = null)
    {
        parent::__construct();

        $this->parameters = [
            'bundle' => $bundle,
            'controller' => $controller,
            'name' => $name,
            'format' => $format,
            'engine' => $engine,
        ];
    }

    /**
     * Returns the path to the template
     *  - as a path when the template is not part of a bundle
     *  - as a resource when the template is part of a bundle.
     *
     * @return string A path to the template or a resource
     */
    public function getPath()
    {
        $controller = str_replace('\\', '/', $this->get('controller'));

        $path = (empty($controller) ? '' : $controller.'/').$this->get('name').'.'.$this->get('format').'.'.$this->get('engine');

        return empty($this->parameters['bundle']) ? 'views/'.$path : '@'.$this->get('bundle').'/Resources/views/'.$path;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogicalName()
    {
        return sprintf('%s:%s:%s.%s.%s', $this->parameters['bundle'], $this->parameters['controller'], $this->parameters['name'], $this->parameters['format'], $this->parameters['engine']);
    }
}
