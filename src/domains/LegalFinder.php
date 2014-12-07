<?php
namespace water\domains;

use Goose\Client;
use water\api\Readability;

class LegalFinder{
    public static $legalwords = [
        "terms",
        "privacy",
        "legal",
        " tos ", //Don't match "photos"

    ];
    public static function getLegalDomain($domain){
        $file = @file_get_contents("http://" . $domain);
        return ($file !== false ? LegalFinder::getLegal($file, "http://" . $domain) : false);
    }
    /*
     * Do NOT call this function to get a URL, use getLegalURL
     */
    public static function getLegal($text, $url = null){
        preg_match_all('`<a [^>]*href="(.*?)">(.*?)</a>`', $text, $matches);
        $dom = new \DOMDocument();
        @$dom->loadHTML($text);
        $links = $dom->getElementsByTagName('a');
        $final = [];
        foreach($links as $link){
            $path = $link->attributes->getNamedItem("href")->value;
            foreach(LegalFinder::$legalwords as $word) {
                if (strpos(strtolower($link->textContent), $word) !== false){
                    $path = strpos($path, '/') === 0 ? $url . $path : $path; //Handle relative links
                    $text = LegalFinder::getTextURL($path);
                    if($text === false) continue;
                    $final[] = [
                        "name" => $link->textContent,
                        "url" => $path,
                        "text" => $text["content"],
                        "updated" => time(),
                        "summary" => "", //TODO
                        "active" => true,
                        "drafts" => []
                    ];
                    break;
                }
            }
        }
        return $final;

    }
    public static function getUpdatedDoc($url){
        $text = LegalFinder::getTextURL($url);
        if($text !== false){
            return [
                "text" => $text["content"],
                "summary" => "", //TODO
                "updated" => time(),
                "active" => true
            ];
        }
        else{
            return false;
        }
    }
    public static function getTextURL($url){
        $goose = new Client();
        $article = $goose->extractContent($url);
        return ["content" => $article->getCleanedArticleText()];
    }
}