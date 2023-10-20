<?php

namespace Laravia\Heart\App\Classes;

use Illuminate\Support\Facades\Blade;
use Laravia\Heart\App\Laravia;
use Laravia\Heart\App\LaraviaPackage;

class Parser
{
    protected string $text = "";
    protected string $search;
    protected string $replace;
    protected string $class;
    protected string $embed;
    protected string $embedClass = Embed::class;
    protected array $options = [];

    public function setOptions($options): Parser
    {
        if ($options) {
            $this->options = $options;
        }
        return $this;
    }

    public function setText($var = ""): Parser
    {
        if ($var) {
            $this->text = $var;
        }
        return $this;
    }

    public function parse(): Parser
    {
        foreach (Laravia::getDataFromConfigByKey('parsers') as $parser) {

            $this->search = data_get($parser, 0, "");
            $this->replace = data_get($parser, 1, "");
            $this->class = data_get($parser, 2, "");
            $this->embed = data_get($parser, 3, "");
            $this->embedClass = data_get($parser, 4, "");

            if (!$this->class) {
                $this->parseWithoutClass();
            }

            if ($this->class) {
                $this->parseWithClass();
            }

            if ($this->embed) {
                $this->parseEmbed();
            }
        }

        return $this;
    }

    public function parseWithoutClass(): void
    {
        $this->text = preg_replace_callback(
            '/\[\[' . $this->search . '\]\]/',
            function ($matches) {
                return str_replace(data_get($matches, 0), $this->replace, $this->text);
            },
            $this->text
        );
    }

    public function parseWithClass(): void
    {
        $this->text = preg_replace_callback(
            '/\[\[' . $this->search . ':(.*?)\]\]/',
            function ($matches) {
                $id = data_get($matches, 1);
                $data = app($this->class)->find($id);
                if ($data) {
                    return $data->{$this->replace};
                }
            },
            $this->text
        );
    }

    public function parseEmbed(): void
    {
        $this->text = preg_replace_callback(
            '/\[\[' . $this->search . ':(.*?)\]\]/',
            function ($matches) {
                $what = $this->search;
                $code = data_get($matches, 1);
                $data = app($this->embedClass)->parse($what, $code);
                if ($data) {
                    return $data;
                }
            },
            $this->text
        );
    }

    public function render(): string
    {
        $this->parse();

        if (!data_get($this->options, 'html', true)) {
            return $this->text;
        }

        if ($this->text) {
            return Blade::render('<x-markdown theme="github-dark">' . $this->text . '</x-markdown>');
        }

        return "";
    }
}
