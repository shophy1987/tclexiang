<?php

namespace shophy\tclexiang;

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client;
use WoohooLabs\Yang\JsonApi\Client\JsonApiClient;
use WoohooLabs\Yang\JsonApi\Response\JsonApiResponse;

abstract class CorpApi extends Api
{
    use traits\DocTrait;
    use traits\QuestionTrait;
    use traits\ThreadTrait;
    use traits\CategoryTrait;
    use traits\CommentTrait;
    use traits\LikeTrait;
    use traits\TeamTrait;
    use traits\PointTrait;
    use traits\AttachmentTrait;
    use traits\VideoTrait;
    use traits\LiveTrait;
    use traits\ClazzTrait;
    use traits\CourseTrait;
    use traits\CertificateRewardTrait;
    use traits\ShareTrait;
    use traits\ExamTrait;
    use traits\ContactTrait;

	const VERSION = 'v1';
	const MAIN_URL = 'https://lxapi.lexiangla.com/cgi-bin';
    const ACCESS_TOKEN = 'LX-ACCESS-TOKEN-';

    protected $key;
    protected $app_secret;
    protected $access_token = '';

    protected $staff_id;
    protected $listeners;

    protected $response;

    public function __construct($app_key = '', $app_secret = '')
    {
    	helpers\Utils::checkNotEmptyStr($app_key, 'app_key');
        helpers\Utils::checkNotEmptyStr($app_secret, 'app_secret');

        $this->key = $app_key;
        $this->app_secret = $app_secret;
    }

    public function get($uri, $data = [])
    {
        if ($data) {
            $uri .= ('?' . http_build_query($data));
        }
        return $this->request('GET', $uri);
    }

    public function post($uri, $data = [])
    {
        return $this->request('POST', $uri, $data);
    }

    public function put($uri, $data = [])
    {
        return $this->request('PUT', $uri, $data);
    }

    public function patch($uri, $data = [])
    {
        return $this->request('PATCH', $uri, $data);
    }

    public function delete($uri, $data = [])
    {
        return $this->request('DELETE', $uri, $data);
    }

    public function request($method, $uri, $data = [])
    {
        $headers["Authorization"] = 'Bearer ' . $this->getAccessToken();
        $headers["StaffID"] = $this->staff_id;
        if (!empty($this->listeners)) {
            $data['meta']['listeners'] = $this->listeners;
            $this->listeners = [];
        }
        if (!empty($data)) {
            $headers["Content-Type"] = 'application/vnd.api+json';
        }
        $request = new Request($method, self::MAIN_URL . '/' . self::VERSION . '/' . $uri, $headers, json_encode($data));
        $client = new JsonApiClient(new Client());

        $this->response = $client->sendRequest($request);

        if ($this->response->getStatusCode() >= 400) {
            return json_decode($this->response->getBody()->getContents(), true);
        }
        if ($this->response->getStatusCode() == 204) {
            return [];
        }
        if (in_array($this->response->getStatusCode(), [200, 201, 202])) {
            return $this->response->document()->toArray();
        }
    }

    public function response()
    {
        return $this->response;
    }

    public function forStaff($staff_id)
    {
        $this->staff_id = $staff_id;
        return $this;
    }

    public function setListeners($listeners)
    {
        if (is_array($listeners)) {
            $this->listeners = $listeners;
        }
        return $this;
    }

    public function getAccessToken()
    {
        if ($this->access_token) {
            return $this->access_token;
        }

        $cache_key = self::ACCESS_TOKEN . $this->key;
        $this->access_token = $this->getCache($cache_key);
        if ($this->access_token) {
            return $this->access_token;
        }

        $options = ['json' => [
            'grant_type' => 'client_credentials',
            'app_key' => $this->key,
            'app_secret' => $this->app_secret
        ]];
        $client = new \GuzzleHttp\Client();
        $response = $client->post(self::MAIN_URL . '/token', $options);
        $response = json_decode($response->getBody()->getContents(), true);
        $this->access_token = $response['access_token'];
        $this->setCache($cache_key, $this->access_token, $response['expires_in']);
        return $this->access_token;
    }

    public function setAccessToken($access_token)
    {
        helpers\Utils::checkNotEmptyStr($access_token, 'access_token');
        $this->access_token = $access_token;
    }

    /**
     * 上传附件
     * @param $staff_id
     * @param $type
     * @param $file
     * @return mixed
     */
    public function postAsset($staff_id, $type, $file)
    {
        $data = [
            [
                'name' => 'file',
                'contents' => $file,
            ],
            [
                'name' => 'type',
                'contents' => $type
            ]
        ];
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('POST', self::MAIN_URL . '/' . self::VERSION . '/assets', [
            'multipart' => $data,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $staff_id,
            ],
        ]);
        return json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * 请求乐享获取腾讯云签名参数
     * @param $file_name
     * @param $type
     * @return mixed
     */
    private function getDocCOSParam($file_name, $type)
    {
        $data = [
            'filename' => $file_name,
            'type' => $type
        ];
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('POST', self::MAIN_URL . '/' . self::VERSION . '/docs/cos-param', [
            'json' => $data,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $this->staff_id,
            ],
        ]);
        return json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * 上传文件至腾讯云cos上
     * @param $file_path
     * @param $upload_type
     * @return array|bool [etag, state]
     */
    private function postCosFile($file_path, $upload_type)
    {
        $filename = pathinfo($file_path, PATHINFO_BASENAME);

        $cos_param = $this->getDocCOSParam($filename, $upload_type);

        if (empty($cos_param['options']) || empty($cos_param['object'])) {
            return false;
        }

        $object = $cos_param['object'];
        $object['filepath'] = $file_path;

        return [$this->qcloudPutObject($object, $cos_param['options']), $object['state']];
    }

    /**
     * 直接调用腾讯云COS的putObject接口上传文件。
     * https://cloud.tencent.com/document/product/436/7749
     * @param $object
     * @param $options
     * @return string 上传文件内容的 MD5 值
     */
    private function qcloudPutObject($object, $options)
    {
        $key = $object['key'];
        $url = 'http://' . $options['Bucket'] . '.cos.' . $options['Region'] . '.myqcloud.com/' . $key;

        $headers = [
                'Authorization' => $object['auth']['Authorization'],
                'x-cos-security-token' => $object['auth']['XCosSecurityToken']
            ] + $object['headers'];

        $raw_request_headers = [];
        foreach ($headers as $key => $header) {
            $raw_request_headers[] = $key . ":" . $header;
        }

        $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_HTTPHEADER, $raw_request_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //设置请求方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($object['filepath']));//设置提交的字符串

        $output = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        // 根据头大小去获取头信息内容
        $raw_response_headers = explode("\r\n", trim(substr($output, 0, $header_size)));
        foreach ($raw_response_headers as $key => $raw_response_header) {
            if (stripos($raw_response_header, 'ETag') === 0) {
                list($item, $value) = explode(":", $raw_response_header);
                $etag = trim(trim($value), '"');
                return $etag;
            }
        }
    }

    /**
     * 批量同步员工生日和入职日
     * @param $staffs
     * @return mixed
     */
    public function putStaffsAnniversaries($staffs)
    {
        $client = new \GuzzleHttp\Client();
        $this->response = $client->request('PUT', self::MAIN_URL . '/' . self::VERSION . '/wish/staffs-anniversaries', [
            'json' => compact('staffs'),
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'StaffID' => $this->staff_id,
            ],
        ]);
        return json_decode($this->response->getBody()->getContents(), true);
    }
}
