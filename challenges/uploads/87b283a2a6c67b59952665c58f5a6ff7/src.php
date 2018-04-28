 <?php

class Template
{
    public $cacheFile = '/tmp/cachefile';
    public $template = '<divÙ[ÛÛYH˜XÚÈ	\ÏÙ]‚s° ¢V&Æ–2gVæ7F–öâõö6öç7G'V7B‚FFFÒçVÆÂ¢°¢FFFÒGF†—2ÓæÆöDFF‚FFF“°¢GF†—2ÑÉ•¹‘•È ‘‘…Ñ„¤ì(€€€ô((€€€ÁÕ‰±¥Œ™Õ¹Ñ¥½¸±½…‘…Ñ„ ‘‘…Ñ„¤(€€€ì(€€€€€€€¥˜€¡ÍÕ‰ÍÑÈ ‘‘…Ñ„°€À°€È¤€„ôô€<èœ€˜˜€…ÁÉ•}µ…Ñ  œ½<éqép¼¼œ°€‘‘…Ñ„¤¤ì(€€€€€€€€€€€É•ÑÕÉ¸Õ¹Í•É¥…±¥é” ‘‘…Ñ„¤ì(€€€€€€€ô(€€€€€€€É•ÑÕÉ¸mtì(€€€ô((€€€ÁÕ‰±¥Œ™Õ¹Ñ¥½¸É•…Ñ•…¡” ‘™¥±”€ô¹Õ±°°€‘ÑÁ°€ô¹Õ±°¤(€€€ì(€€€€€€€€‘™¥±”€ô€‘™¥±”€üü€‘Ñ¡¥Ì´cacheFile;
        $tpl = $tpl ?? $this->template;
        file_put_contents($file, $tpl);
    }

    public function render($data)
    {
        return sprintf(
            $this->template,
            htmlspecialchars($data['name'])
        );
    }

    public function __destruct()
    {
        $this->createCache();
    }
}
