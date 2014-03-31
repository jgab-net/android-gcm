<?php namespace JgabNet\AndroidGcm;
/**
 * User: jgab
 * Date: 10/03/14
 * Time: 22:00
 */

class AndroidGcm {

    protected $apiKey;
    protected $apiUrl;

    protected $apiResponse;

    public function __construct($apiKey, $apiUrl){
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }

    public function addRegistrationId($registrationId, $userId){
        $registration=\DB::table('android_gcm')
            ->where('registration_id',$registrationId)
            ->where('user_id',$userId)
            ->first();

        if(is_null($registration)){
            $timestamp = new \DateTime();

            \DB::table('android_gcm')->insert(array(
                'registration_id' => $registrationId,
                'user_id' => $userId,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ));
        }
    }

    public function send($userIds, \Closure $callback){


        $registrationIds = \DB::table('android_gcm')->
            whereIn('user_id',$userIds)->lists('registration_id');

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => array(
                    'Authorization: Key='.$this->apiKey,
                    'Content-Type: application/json'
                ),
                'content' => json_encode(array(
                    'registration_ids' => $registrationIds,
                    'collapse_key' => 'Updates Available'
                ))
            )
        ));

        $result = file_get_contents($this->apiUrl, false, $context);

        if($result){
            $this->apiResponse = json_decode($result);

            /*TODO
            verificar los canonical para saber cuales notificaciones llegaron realmente
            TODO luego actualizar los registrations_ids cambiados y pasar solo al closure
            los id de usuario que realmente recivieron notificacion
            */

            $callback($userIds);

        }
    }

    public function getApiResponse(){
        return $this->apiResponse;
    }

} 