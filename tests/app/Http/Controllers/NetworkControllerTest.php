<?php

class NetworkControllerTest extends TestCase
{

    public function testNetworks()
    {
        $this->json('GET', '/api/networks?url=rede-a.com', [])
             ->seeJson([
                'id' => 1,
                'name' => 'Rede A',
             ]);

             $this->json('GET', '/api/networks?url=rede-k.com', [])
             ->seeJson([
                'message' => 'Not Found'
            ]);

    }

    public function testUpdateNetworkWithoutNetworkConfigID()
    {
        $token = AuthControllerTest::getToken($this);
        $headers = ['Authorization' => 'Bearer ' . $token->token];

        $jsonForUpdate =
            '{
              "name": "Rede B",
              "url": "rede-b.com",
              "alternate_url": "rede-b-alterada.com",
              "available": false,
              "old_id": null,
              "created_at": "2018-07-20 19:06:47",
              "updated_at": "2018-07-23 21:33:36",
              "deleted_at": null,
              "network_style_id": null,
              "network_config": {
                "use_revisor": true,
                "allow_anonymous_oda_suggestion": true,
                "allow_class_plan": true,
                "allow_sign_in": true,
                "automatic_approve_comments": true,
                "contact_email": "teste@teste.com",
                "ga_code": null,
                "is_provider": false,
                "lists_active": false,
                "popular_objects_active": false,
                "slogan": null,
                "suggest_objects_active": false,
                "suggest_thanks": "Thanks",
                "survey_enabled": false,
                "survey_thanks_1": null,
                "survey_thanks_2": null,
                "survey_url": null,
                "deleted_at": null,
                "home_facets": []
              }
            }';

        $jsonForUpdate = (array) json_decode($jsonForUpdate);

        $result = $this->json('PUT', '/api/2/network', $jsonForUpdate, $headers);

        $jsonResult = (json_decode($result->response->getContent()));
        $this->assertEquals(2, $jsonResult->id);
        $this->assertEquals("rede-b-alterada.com", $jsonResult->alternate_url);
        $this->assertEquals(true, $jsonResult->network_config->use_revisor);
        $this->assertEquals(true, $jsonResult->network_config->allow_anonymous_oda_suggestion);

    }

    public function testUpdateNetworkWithNetworkConfigID()
    {
        $token = AuthControllerTest::getToken($this);
        $headers = ['Authorization' => 'Bearer ' . $token->token];

        $jsonForUpdate =
            '{
              "name": "Rede A",
              "url": "rede-a.com",
              "alternate_url": "rede-a-alterada.com",
              "available": false,
              "old_id": null,
              "created_at": "2018-07-20 19:06:47",
              "updated_at": "2018-07-23 21:33:36",
              "deleted_at": null,
              "network_config_id": 2,
              "network_style_id": null,
              "network_config": {
                "use_revisor": false,
                "allow_anonymous_oda_suggestion": false,
                "allow_class_plan": false,
                "allow_sign_in": false,
                "automatic_approve_comments": true,
                "contact_email": "teste@teste.com",
                "ga_code": null,
                "is_provider": false,
                "lists_active": false,
                "popular_objects_active": false,
                "slogan": null,
                "suggest_objects_active": false,
                "suggest_thanks": "Thanks",
                "survey_enabled": false,
                "survey_thanks_1": null,
                "survey_thanks_2": null,
                "survey_url": null,
                "deleted_at": null,
                "home_facets": []
              }
            }';

        $jsonForUpdate = (array) json_decode($jsonForUpdate);

        $result = $this->json('PUT', '/api/1/network', $jsonForUpdate, $headers);

        $jsonResult = (json_decode($result->response->getContent()));
        $this->assertEquals(1, $jsonResult->id);
        $this->assertEquals("rede-a-alterada.com", $jsonResult->alternate_url);
        $this->assertEquals(false, $jsonResult->network_config->use_revisor);
        $this->assertEquals(false, $jsonResult->network_config->allow_anonymous_oda_suggestion);
        $this->assertEquals(false, $jsonResult->network_config->allow_class_plan);
        $this->assertEquals(false, $jsonResult->network_config->allow_sign_in);
    }

    public function testUpdateNetworkWithNetworkConfigVisualData()
    {
        $token = AuthControllerTest::getToken($this);
        $headers = ['Authorization' => 'Bearer ' . $token->token];

        $jsonForUpdate =
            '{
              "name": "Rede A",
              "url": "rede-a.com",
              "alternate_url": "rede-a-alterada.com",
              "available": false,
              "old_id": null,
              "created_at": "2018-07-20 19:06:47",
              "updated_at": "2018-07-23 21:33:36",
              "deleted_at": null,
              "network_config_id": 2,
              "network_style_id": null,
              "network_config": {
                "use_revisor": false,
                "allow_anonymous_oda_suggestion": false,
                "allow_class_plan": false,
                "allow_sign_in": false,
                "automatic_approve_comments": true,
                "contact_email": "teste@teste.com",
                "ga_code": null,
                "is_provider": false,
                "lists_active": false,
                "popular_objects_active": false,
                "slogan": null,
                "suggest_objects_active": false,
                "suggest_thanks": "Thanks",
                "survey_enabled": false,
                "survey_thanks_1": null,
                "survey_thanks_2": null,
                "survey_url": null,
                "deleted_at": null,
                "url_logo": "https://cybermap.kaspersky.com/",
                "url_imagem_principal": "http://map.norsecorp.com",
                "cor_primaria": "#FFF",
                "cor_secundaria": "#000",
                "network_config_menu_id": 1,
                "network_rede_social_id": 1,
                "odas_populares": true,
                "sugestao_odas": true,
                "listas": true
              }
            }';

        $jsonForUpdate = (array) json_decode($jsonForUpdate);

        $result = $this->json('PUT', '/api/1/network', $jsonForUpdate, $headers);

        $jsonResult = (json_decode($result->response->getContent()));
        $this->assertEquals(1, $jsonResult->id);
        $this->assertEquals("rede-a-alterada.com", $jsonResult->alternate_url);
        $this->assertEquals(false, $jsonResult->network_config->use_revisor);
        $this->assertEquals(false, $jsonResult->network_config->allow_anonymous_oda_suggestion);
        $this->assertEquals(false, $jsonResult->network_config->allow_class_plan);
        $this->assertEquals(false, $jsonResult->network_config->allow_sign_in);
        $this->assertEquals("https://cybermap.kaspersky.com/", $jsonResult->network_config->url_logo);
        $this->assertEquals(1, $jsonResult->network_config->network_config_menu_id);
    }

}
