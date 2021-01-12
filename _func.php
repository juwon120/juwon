<?php

    class WEBParser
    {
        private $httph = 'Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; rv:11.0) like Gecko';

        public function splits($data, $first, $end, $num = 1)
        {
            $temp = @explode($first, $data);
            $temp = @explode($end, $temp[$num]);
            $temp = $temp[0];
            return $temp;
        }

        public function WEBParsing($url, $cookie = NULL, $headershow = TRUE, $postparam = NULL, $otherheader = NULL)
        {
            $ch = curl_init();

            $opts = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url,
	 CURLOPT_REFERER => 'https://www.yesfile.com/',
                CURLOPT_TIMEOUT => 60,
                CURLOPT_CONNECTTIMEOUT => 30,
                // CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_HEADER => $headershow,
                CURLOPT_USERAGENT => $this->httph,
            );

            curl_setopt_array($ch, $opts);

            if ($otherheader) curl_setopt($ch, CURLOPT_HTTPHEADER, $otherheader);

            if ($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);

            if ($postparam)
            {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postparam);
            }

            $data = curl_exec($ch);
            if (curl_errno($ch))
                return false;
            curl_close($ch);
            return ($data) ? $data : false;
        }

       public function Cash($id, $pw, $culture_id, $culture_pw, $no1, $no2, $no3, $no4)
       {
	$url = 'https://www.yesfile.com/';
	$data = $this->WEBParsing($url, '', false);

	$login_key = $this->splits($data, 'LOGIN_KEY = "', '"');

	$url = 'https://www.yesfile.com/login/';
	$data = $this->WEBParsing($url, '', false, 'pg_mode=login&new_home=yes&go_url=%2F&userid='.$id.'&userpw='.$pw.'&login_key='.$login_key.'&x=16&y=27');

	$packet = 'CPID=CBP40956&RETURNURL=&HOMEURL=&ORDERNO='.$id.'_1596523050_24366&PRODUCTTYPE=1&BILLTYPE=1&AMOUNT=5000&IPADDRESS=127.0.0.1&USERID='.$id.'&USERNAME=&PRODUCTCODE=495&PRODUCTNAME=%C6%F7%C0%CE%C6%AE%C3%E6%C0%FC+5%2C000%BF%F8%28%C3%B9%29&RESERVEDINDEX1=127.0.0.1&RESERVEDINDEX2=95&RESERVEDSTRING=PC&DIRECTRESULTFLAG=&SET_LOGO=&RESULTCODE=&ERRORMESSAGE=&CERTDATE=&DAOUTRX=&LIMITAMOUNT=&CERTID=&CERTNO=&AUTHTYPE=&AUTHNAME=&CERTIFY=NTk0MmE3ODIzODkwMjBiMTdkY2FiOGY1NjVkNDkzNzE3ZjEyZWE5MzFkOTczNzE2OTIzMDkyMjAx%250AM2NiNDFmYg%253D%253D&CERTIFYA=9f8ac753bdde9f8749a0884198fd050c18cc946c74708d7c501b765cc2c670cd2d431c14d62e1b4d12874bd0a1bd9cef&CERTIFYB=043e7016ceaff607670feb663ac05da5431c9dd1a7c4575bf61d34fb38e8ffdde7eb8593014f0e3246702bdb089513e7625ad67afeeeb576a9533e4bc45787d42f25583d59e128e1f49963348f503e710eac65f8d9e519fdb6ac6a8533ee94f4&STEMP=c914894042af37c7b5159989722dac20356184034b27a329d0808829fb3a9e1b75dd99f4586c36667d237c98b2465c0327a529c0003e01583f89b1cac21b02b33bed790801a9f7ef8fb9eeb8b7bc73ed28ba39500f5659eb226c318c68ceca8f263f032218c5459b8d4ed568b743e72e&CULTURECASH_ID='.$culture_id.'&CULTURECASH_PWD='.$culture_pw.'&EMAIL=';

	if(!preg_match('/alert/', $data) && !preg_match('/login.html/', $data)) {

		$url = 'https://ssl.daoupay.com/culture2/CultureCertDo.jsp';
		$data = $this->WEBParsing($url, '', false, $packet);

		$trx_id = $this->splits($data, 'DAOUTRX.value', ';');
		$trx_id = str_replace('=', '', $trx_id);
		$trx_id = trim(str_replace('"', '', $trx_id));

		$cert_date = $this->splits($data, 'CERTDATE.value', ';');
		$cert_date = str_replace('=', '', $cert_date);
		$cert_date = trim(str_replace('"', '', $cert_date));
		$cert_date = substr($cert_date, 0, 8);

		$cert_no = $this->splits($data, 'CERTNO.value', ';');
		$cert_no = str_replace('=', '', $cert_no);
		$cert_no = trim(str_replace('"', '', $cert_no));

		$packet = 'frmAction=recv&trxid='.$trx_id.'&reserved=&samplewidth=429&scrollheight=649&exmembercode=Q300021&submembercode=daoutest&exmemberid='.$id.'&custid=18687255&userid='.$culture_id.'&usernm=&certno='.$cert_no.'&membercontrolcode='.$trx_id.'&levydate='.$cert_date.'&levytime=034000&contentsname=%C6%F7%C0%CE%C6%AE%C3%E6%C0%FC+5%2C000%BF%F8%28%C3%B9%29&levyamount=1&unit=%B0%C7&amount=5000&return_url=https%3A%2F%2Fssl.daoupay.com%2Fculture%2FCultureMChargeDo_iframe_return.jsp&confirm_type=1&mcash=';

		$url = 'http://bill.cultureland.co.kr/mcash/custom/mcash_charge.asp';
		$data = $this->WEBParsing($url, '', false, $packet);

		$hash_no = $this->splits($data, 'hashNo" value="', '"');
		$packet = 'exmembercode=Q300021&submembercode=daoutest&exmemberid='.$id.'&custid=18687255&userid='.$culture_id.'&usernm=&certno='.$cert_no.'&return_target=&return_url=https%3A%2F%2Fssl.daoupay.com%2Fculture%2FCultureMChargeDo_iframe_return.jsp&trxid='.$trx_id.'&reserved=&hashNo='.$hash_no.'&tmpChargeLine=0&cno11='.$_GET['no1'].'&cno12='.$_GET['no2'].'&cno13='.$_GET['no3'].'&cno14='.$_GET['no4'].'&cno21=&cno22=&cno23=&cno24=&cno31=&cno32=&cno33=&cno34=&cno41=&cno42=&cno43=&cno44=&cno51=&cno52=&cno53=&cno54=';

		$url = 'http://bill.cultureland.co.kr/mcash/custom/charge_process.asp';
		$data = $this->WEBParsing($url, '', false, $packet);

		return print($this->splits($data, '<span class="txt_result_point">', '<br>'));
	}
       }
    }