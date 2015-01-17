<?php

namespace Rapi;

defined('PHPLISTINIT') || die;

/**
 * Class Messages
 * Manage phplist Messages
 */
class Messages{

    static function messageGet( $id=0 ) {
        if ( $id==0 ) $id = $_REQUEST['id'];
        Common::select( 'Message', "SELECT * FROM " . $GLOBALS['table_prefix'] . "message WHERE id=" . $id . ";", true );
    }

    static function messagesGet() {
        Common::select( 'Messages', "SELECT * FROM " . $GLOBALS['table_prefix'] . "message ORDER BY Modified DESC;" );
    }

    /**
     * Adds a new message/campaing.
     * Parameters:
     * [*subject] {string}
     * [*fromfield] {string}
     * [*replyto] {string}
     * [*message] {string}
     * [*textmessage] {string}
     * [*footer] {string}
     * [*status] {string}
     * [*sendformat] {string}
     * [*template] {string}
     * [*embargo] {string}
     * [*rsstemplate] {string}
     * [*owner] {string}
     * [htmlformatted] {string}
     * Returns:
     * The message added.
     *
     */
    static function messageAdd(){

        $sql = "INSERT INTO " . $GLOBALS['table_prefix'] . "message (subject, fromfield, replyto, message, textmessage, footer, entered, status, sendformat, template, embargo, rsstemplate, owner, htmlformatted ) VALUES ( :subject, :fromfield, :replyto, :message, :textmessage, :footer, now(), :status, :sendformat, :template, :embargo, :rsstemplate, :owner, :htmlformatted );";
        try {
            $db = PDO::getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("subject", $_REQUEST['subject'] );
            $stmt->bindParam("fromfield", $_REQUEST['fromfield'] );
            $stmt->bindParam("replyto", $_REQUEST['replyto'] );
            $stmt->bindParam("message", $_REQUEST['message'] );
            $stmt->bindParam("textmessage", $_REQUEST['textmessage'] );
            $stmt->bindParam("footer", $_REQUEST['footer'] );
            $stmt->bindParam("status", $_REQUEST['status'] );
            $stmt->bindParam("sendformat", $_REQUEST['sendformat'] );
            $stmt->bindParam("template", $_REQUEST['template'] );
            $stmt->bindParam("embargo", $_REQUEST['embargo'] );
            $stmt->bindParam("rsstemplate", $_REQUEST['rsstemplate'] );
            $stmt->bindParam("owner", $_REQUEST['owner'] );
            $stmt->bindParam("htmlformatted", $_REQUEST['htmlformatted'] );
            $stmt->execute();
            $id = $db->lastInsertId();
            $db = null;
            Messages::messageGet( $id );
        } catch(\PDOException $e) {
            Response::outputError($e);
        }

    }

    /**
     * Updates existing message/campaign.
     * Parameters:
     * [*id] {integer}
     * [*subject] {string}
     * [*fromfield] {string}
     * [*replyto] {string}
     * [*message] {string}
     * [*textmessage] {string}
     * [*footer] {string}
     * [*status] {string}
     * [*sendformat] {string}
     * [*template] {string}
     * [*embargo] {string}
     * [*rsstemplate] {string}
     * [owner] {string}
     * [htmlformatted] {string}
     * Returns:
     * The message added.
     *
     */
    static function messageUpdate( $id = 0 ){

        if ( $id == 0 ) $id = $_REQUEST['id'];
        $sql = "UPDATE " . $GLOBALS['table_prefix'] . "message SET subject=:subject, fromfield=:fromfield, replyto=:replyto, message=:message, textmessage=:textmessage, footer=:footer, status=:status, sendformat=:sendformat, template=:template, sendstart=:sendstart, rsstemplate=:rsstemplate, owner=:owner, htmlformatted=:htmlformatted WHERE id=:id;";
        try {
            $db = PDO::getConnection();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("id", $id );
            $stmt->bindParam("subject", $_REQUEST['subject'] );
            $stmt->bindParam("fromfield", $_REQUEST['fromfield'] );
            $stmt->bindParam("replyto", $_REQUEST['replyto'] );
            $stmt->bindParam("message", $_REQUEST['message'] );
            $stmt->bindParam("textmessage", $_REQUEST['textmessage'] );
            $stmt->bindParam("footer", $_REQUEST['footer'] );
            $stmt->bindParam("status", $_REQUEST['status'] );
            $stmt->bindParam("sendformat", $_REQUEST['sendformat'] );
            $stmt->bindParam("template", $_REQUEST['template'] );
            $stmt->bindParam("sendstart", $_REQUEST['sendstart'] );
            $stmt->bindParam("rsstemplate", $_REQUEST['rsstemplate'] );
            $stmt->bindParam("owner", $_REQUEST['owner'] );
            $stmt->bindParam("htmlformatted", $_REQUEST['htmlformatted'] );
            $stmt->execute();
            $db = null;
            Messages::messageGet( $id );
        } catch(\PDOException $e) {
            Response::outputError($e);
        }

    }

	static function formtokenGet(){
		$key = md5(time().mt_rand(0,10000));
		Sql_Query(sprintf('insert into %s (adminid,value,entered,expires) values(%d,"%s",%d,date_add(now(),interval 1 hour))',
		$GLOBALS['tables']['admintoken'],$_SESSION['logindetails']['id'],$key,time()),1);
		$response = new Response();
		$response->setData('formtoken', $key);
		$response->output();
	}
}
