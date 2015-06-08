<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Ftl_Mail
{
	const MAIL_PRIORITY_HIGHEST	= '1 (Highest)';

	const MAIL_PRIORITY_HIGH	= '2 (High)';

	const MAIL_PRIORITY_NORMAL	= '3 (Normal)';

	const MAIL_PRIORITY_LOW		= '4 (Low)';

	const MAIL_PRIORITY_LOWEST	= '5 (Lowest)';



	private $_headers	= '';

	private $_header	= array();



	private $_params	= array(

		'from'			=> '',

		'to'			=> array(),

		'cc'			=> array(),

		'bcc'			=> array(),

		'subject'		=> '',

		'body'			=> '',



		'reply-to'		=> '',

		'receipt'		=> false,



		'charset'		=> 'UTF-8',

		'ctencoding'            => '8bit',



		'priority'		=> self::MAIL_PRIORITY_NORMAL,



		'organization'	=> false

	);


	public function __construct( $params = array() ) {

		$this->_params = Ftl_ArrayUtil::merge( $this->_params, $params );

	}



	public function from( $from ) {

		$this->_params[ 'from' ] = $from;

	}



	public function to( $to ) {

		$to = Ftl_StringUtil::toArray($to);

		$this->_params[ 'to' ] = array_unique( array_merge( $this->_params[ 'to' ], $to ) );

	}



	public function cc( $cc ) {

		$cc = Ftl_StringUtil::toArray($cc);

		$this->_params[ 'cc' ] = array_unique( array_merge( $this->_params[ 'cc' ], $cc ) );

	}



	public function bcc( $bcc ) {

		$bcc = Ftl_StringUtil::toArray($bcc);

		$this->_params[ 'bcc' ] = array_unique( array_merge( $this->_params[ 'bcc' ], $bcc ) );

	}



	public function replyTo( $address ) {

		$this->_params[ 'reply-to' ] = $address;

	}



	public function receipt() {

		$this->_params[ 'receipt' ] = true;

	}



	public function subject( $subject ) {

		$this->_params[ 'subject' ] = $subject;

	}



	public function body( $body, $charset = '' ) {

		$this->_params[ 'body' ] = $body;

	}



	public function charset( $charset ) {



		$this->_params[ 'charset' ] = strtolower( $charset );



		if( $this->_params[ 'charset' ] != 'us-ascii' ) {

			$this->_params[ 'ctencoding' ] = '8bit';

		}



	}



	public function organization( $org ) {

		$this->_params[ 'organization' ] = $org;

	}



	public function priority( $value ) {

		$this->_params[ 'priority' ] = $value;

	}



	public function send() {



		$this->build();


		return mail(

			$this->_params[ 'to' ],

			$this->_params[ 'subject' ],

			$this->_params[ 'body' ],

			$this->_headers

		);



	}



	private function build() {



		// Build Emails

		$this->_params[ 'to' ] 	= implode( ',', Ftl_StringUtil::toArray($this->_params[ 'to' ]) );

		$cc 			= implode( ',', Ftl_StringUtil::toArray($this->_params[ 'cc' ]) );
		$bcc			= implode( ',', Ftl_StringUtil::toArray($this->_params[ 'bcc' ]) );
		
		if ($cc) $this->_header[ 'CC' ] = $cc;
		if ($bcc) $this->_header[ 'BCC' ] = $bcc;
		
		// Build Configuration

		if( $this->_params[ 'organization' ] !== false ) {

			$this->_header[ 'Organization' ] = stripcslashes( $this->_params[ 'organization' ] );

		}



		if( $this->_params[ 'receipt' ] ) {



			if( isset( $this->_params[ 'reply-to' ] ) ) {

				$this->_header[ 'Disposition-Notification-To' ] = $this->_params[ 'reply-to' ];

			} else {

				$this->_header[ 'Disposition-Notification-To' ] = $this->_params[ 'from' ];

			}



		}



		if( isset( $this->_params[ 'from' ] ) ) {

			$this->_header[ 'From' ] = $this->_params[ 'from' ];

		}



		if( isset( $this->_params[ 'reply-to' ] ) ) {

			$this->_header[ 'Reply-to' ] = $this->_params[ 'reply-to' ];

		}



		$this->_header[ 'Mime-Version' ] = '1.0';

		$this->_header[ 'Content-Type' ] = 'text/html; charset=' . $this->_params[ 'charset' ];

		$this->_header[ 'Content-Transfer-Encoding' ] = $this->_params[ 'ctencoding' ];



		$this->_header[ 'X-Mailer' ] = 'PHP/' . phpversion();





		// Build Headers



		reset( $this->_header );



		foreach( $this->_header as $header => $value ) {

			$this->_headers .= $header . ': ' . $value . "\n";

		}





		// Build Subject



		$this->_params[ 'subject' ] = str_replace( array( "\r", "\n" ), ' ' , $this->_params[ 'subject' ] );

		$this->_params[ 'subject' ] = utf8_decode( $this->_params[ 'subject' ] );

		$this->_params[ 'subject' ] = Ftl_QEncoding::encode( $this->_params[ 'subject' ] );



	}



}





/*

 * Implementation

 *



###############

## Example 1 ##

###############



$mail = new MailUtil( array(

	'from'		=> 'Example Mail <from@example.com>',

	'to'		=> array( 'to1@example.com', 'to2@example.com', 'to3@example.com'),

	'cc'		=> array( 'cc1@example.com', 'cc2@example.com', 'cc3@example.com'),

	'bcc'		=> array( 'bcc1@example.com', 'bcc2@example.com', 'bcc3@example.com'),

	'subject'	=> 'Subject ¿¡áëíÖÚñÑ"!?',

	'body'		=> '<html><body><div><img src="pixel.gif" alt="Page Title ¡¨!ñóä!" />...</body></html>'

) );



$mail->send();



###############

## Example 2 ##

###############



$mail = new MailUtil( array(

	'from'		=> 'from@example.com',

	'to'		=> 'to@example.com',

	'bcc'		=> 'bcc@example.com',

	'subject'	=> 'Subject ¿¡áëíÖÚñÑ"!?',

	'body'		=> 'Email Content ¡ñóä!'

) );



$mail->send();



*/






?>
