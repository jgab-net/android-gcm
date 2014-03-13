Laravel - Android GCM
=

Paquete laravel para enviar notificaciones usando Google Could Message (GCM)
-
http://developer.android.com/google/gcm/gcm.html

Instalando
-
```shell

composer require jgab-net/android-gcm dev-master

```
Publicando configuración 
-
```shell

php artisan config:publish jgab-net/android-gcm
```

El archivo de configuración se publica en app/config/packages/jgab-net/android-gcm/config.php

Coloquen el api_key generado en https://cloud.google.com/console para el servidor
```php

return array(
    'api_key' => 'aqui el api_key'
);

```
Configurando base de datos
-
Es necesario correr la migración del paquete para que se genere la tabla donde se guardaran los tokens (registrations_id) que representan los dispositivos android que recibiran notificaciones
```shell

php artisan migrate --package=jgab-net/android-gcm

```
Es importante que esta migración se ejecute despues de que corras las migraciones de tu proyecto o exista la tabla users en tu sistema, porque se creara una clave foranea con users.id, si no existe la tabla la migración mostrara un error, sin embargo puedes continuar ignorando el error, simplemente perderas la clave foranea
Programando
-
Para almacenar el token(registration_id) solo necesitas agregar la siguiente linea, en el lugar que lo desees (el registration_id se supone estar llegando desde el dispositivo android, y el user_id pertenece al usuario que accedio a la apliación)
```php

AndroidGcm::addRegistrationId($registration_id, $user_id);

```
Si estas usando algún paquete aparte para el manejo de accesos de tu usuario puedes trabajar con un filtro after, ej: 
-- filters.php
```php

Route::filter('android.gcm',function($route, $request, $response){

    /*verificamos que venga de una respuesta json (Response::json())
     y que exista un registration_id en el input */
     
    if($response instanceof \Illuminate\Http\JsonResponse
        && Input::has('registration_id')){
     
        // Obtenemos el contenido de la respuesta
        $content = json_decode($response->getContent());

        AndroidGcm::addRegistrationId(Input::get('registration_id'), $content->user->id);
    }
});

```
La explicación ya se encuentra en el código de ejemplo

-- routes.php
```php

Route::post('auth',  array('after' => 'android.gcm', 'uses' => 'Vendor\Paquete\Controller@method'));
 
```
Notificando
-
Para notificar simplemente ejecutamos el methodo send, el primer valor es un array con los tokens(registration_ids) de los dispositivos a notificar, y el segundo es un callback que recibe los tokens(registration_ids) que realmente fueron notificados

La librería internamete reemplazara los tokens(registration_ids) desactualizados, para que en la proxima ejecución del envio se transmitan las notificaciones a los dispositivos faltantes

```php

AndroidGcm::send($registrationIds,function($successRegistrationIds){  

    /*
      Aquí puedes actualizar la bandera que indique que ya no debes 
      enviar la notificación a los dispositivos
      Puedes usar $successRegistrationIds para valerte de esto.
    */
});

```
Si necesitas algún otro valor dentro del callback puedes pasarlo con (use)


```php

AndroidGcm::send($registrationIds,function($successRegistrationIds) use($otherValue1,$otherValue2){  

    /*
      
    */
});

```
