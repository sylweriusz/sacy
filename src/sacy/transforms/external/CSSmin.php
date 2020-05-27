<?php
namespace sacy\transforms\external;

class CSSMin implements \sacy\Transformer
{
    function transform(string $in_content, ?string $in_file, array $options = []): string
    {
        $input_css  = file_get_contents($in_file);
        $compressor = new \tubalmartin\CssMin\Minifier;
        return $compressor->run($input_css);
    }
}
