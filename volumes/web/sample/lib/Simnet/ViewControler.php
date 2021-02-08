<?php

namespace Simnet;

require_once __DIR__ . '/../../vendor/autoload.php';

class ViewControler
{
    private const TEMPLATE_DIR = __DIR__ . '/../../template/';

    private $filename;
    private $title;

    /**
     * initialize
     *
     * @param string $filename optional
     * @param string $title optional
     * @return bool $result
     */
    public function __construct(?string $filename, ?string $title)
    {
        $this->filename = $filename ?? 'index.php';
        $this->title = $title ?? 'TODO Application';
    }

    public function assign($name, $value)
    {
        $this->assignList[$name] = $value;
    }

    public function __destruct()
    {
        $title = $this->title;
        $header = $this->html_header();
        $script = $this->html_script();
        $footer = $this->html_footer();

        foreach ($this->assignList as $key => $value) {
            $$key = $value;
        }

        require_once self::TEMPLATE_DIR . $this->filename;
    }

    /**
     * return html header
     *
     * @return string
     */
    private function html_header()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$this->title}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
HTML;
    }

    /**
     * return html footer
     *
     * @return string
     */
    private function html_footer()
    {
        return <<<HTML
</body>
</html>
HTML;
    }

    private function html_script()
    {
        return <<<HTML
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
HTML;
    }

}