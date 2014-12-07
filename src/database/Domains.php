<?php
namespace water\database;

class Domains{
    public static function createDomain($domain, $documents = []){
        MongoConnector::getDomainsCollection()->insert([
            "_id" => $domain,
            "added" => time(),
            "documents" => $documents
        ]);
    }
    public static function addDocument($domain, $name, $url, $text, $summarized){
        MongoConnector::getDomainsCollection()->update(["_id" => $domain], [
            '$push' => [
                "documents" => [
                    "name" => $name,
                    "updated" => time(),
                    "url" => $url,
                    "text" => $text,
                    "summary" => $summarized,
                    "active" => true
                ]
            ]
        ]);
    }
    public static function updateDocument($domain, $id, $text, $summarized, $active = true){
        $domain = Domains::getDomain($domain);
        if($domain !== false){
            $domain["documents"][$id] = [
                "name" => $domain["documents"][$id]["name"],
                "updated" => time(),
                "url" => $domain["documents"][$id]["url"],
                "text" => $text,
                "summary" => $summarized,
                "active" => $active
            ];
            Domains::updateDomain($domain);
            return true;
        }
        else{
            return false;
        }
    }
    public static function setDocument($domain, $id, $document){
        $domain = Domains::getDomain($domain);
        if($domain !== false){
            $domain["documents"][$id] = $document;
            Domains::updateDomain($domain);
            return true;
        }
        else{
            return false;
        }
    }
    public static function getDocument($domain, $id){
        $domain = Domains::getDomain($domain);
        if($domain !== false){
            if(isset($domain["documents"][$id])){
                $domain["documents"][$id]["id"] = $id;
                return $domain["documents"][$id];
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    public static function updateDomain($domain){
        //var_dump($domain);
        return MongoConnector::getDomainsCollection()->update([$domain['_id']], $domain) != null ? true : false;
    }
    public static function getDomain($domain){
        $domain = MongoConnector::getDomainsCollection()->findOne(["_id" => $domain]);
        return $domain != null ? $domain : false;
    }
}