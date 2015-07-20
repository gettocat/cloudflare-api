<?php

Class CloudFlare extends CloudFlareApi {

    const HOST_KEY = '';
    const TOKEN = 'youtoken';
    const USER_ID = 'you@email';

    protected static $instance = null;
    protected static $instanceHost = null;

    /**
     * 
     * @return CloudFlare
     */
    public static function get() {
        if (static::$instance == null)
            static::$instance = new static(static::USER_ID, static::TOKEN);

        return static::$instance;
    }

    /**
     * 
     * @param int $page
     * @param int $perpage (max 50 min 5)
     * @param string $order name,email,status
     * @param string $direction desc,asc
     * @param string $status active, pending, initializing, moved, deleted, deactivated
     * @return type
     */
    public function getDomainList($page = 1, $perpage = 50, $status = 'active', $order = 'status', $direction = 'desc') {
        $data = $this->call('zones', array(
            'page' => $page,
            'per_page' => $perpage,
            'status' => $status,
            'order' => $order,
            'direction' => $direction,
        ));


        return $data['result']; //array of domains
    }

    public function addDomain($domain) {
        $data = $this->call('zones', array(
            'name' => $domain,
                ), 'POST');

        //id
        // ["name_servers"]=>	 array(2) {
        //[0]=>	 string(23) "chris.ns.cloudflare.com"
        //[1]=>	 string(21) "mia.ns.cloudflare.com"
        //}

        return $data['result']; //its too loong
    }

    public function removeDomain($domain_id) {
        $data = $this->call('zones/' . $domain_id, array(), 'DELETE');
        return $data['result'];
    }

    public function getDomainInfo($id) {
        $data = $this->call('zones/' . $id, array(), 'GET');
        return $data['result'];
    }

    /**
     * 
     * @param type $id
     * @param type $page
     * @param type $perpage min 5, max 100
     * @param type $order type, name, content, ttl, proxied
     * @param type $direction desc,asc
     * @return type 
     */
    public function getDomainZones($id, $page = 1, $perpage = 100, $order = 'type', $direction = 'desc') {
        $data = $this->call('zones/' . $id . '/dns_records', array(
            'page' => $page,
            'per_page' => $perpage,
            'order' => $order,
            'direction' => $direction
                ), 'GET');
        return $data['result'];
    }

    /**
     * 
     * @param type $id domain id
     * @param type $type A, AAAA, CNAME, TXT, SRV, LOC, MX, NS, SPF
     * @param type $value subdomain.domain.com
     * @param type $content value
     * @return type
     */
    public function addDomainZone($id, $type = 'CNAME', $name = '', $value = '', $params = array()) {


        $d = array(
            'type' => $type,
            'name' => $name,
            'content' => $value
        );

        if ($type == 'MX') {
            $d['priority'] = $params['priority'] ? $params['priority'] : 1;
        }

        $data = $this->call('zones/' . $id . '/dns_records', $d, 'POST');

        return $data['result'];
    }

    public function getDomainZoneInfo($domain_id, $zone_id) {
        $data = $this->call('zones/' . $domain_id . '/dns_records/' . $zone_id, array(), 'GET');
        return $data['result'];
    }

    public function editDomainZone($domain_id, $zone_id, $type, $value = '', $content = '') {
        $data = $this->call('zones/' . $domain_id . '/dns_records/' . $zone_id, array(
            'type' => $type,
            'name' => $value,
            'content' => $content
                ), 'PUT');

        return $data['result'];
    }

    public function removeDomainZone($domain_id, $zone_id) {
        $data = $this->call('zones/' . $domain_id . '/dns_records/' . $zone_id, array(), 'DELETE');
        return $data['result'];
    }

}
