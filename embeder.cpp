//========================================================================
//       Embeder - Make an executable Windows-binary file from a PHP script
//
//       License : PHP License (http://www.php.net/license/3_0.txt)
//       Author : Eric Colinet <e dot colinet at laposte dot net>
//       http://wildphp.free.fr/wiki/doku?id=win32std:embeder
//========================================================================

/* PHP Conf */
#define ZEND_WIN32
#define PHP_WIN32
#define ZTS 1
#define ZEND_DEBUG 0

/* PHP Includes */
#include <php_embed.h>

/* Main */
int main(int argc, char** argv) {
	zval ret_value;
	void ***tsrm_ls;
	int exit_status;
	char *eval_string= "include 'res:///PHP/RUN';";

	php_embed_init(argc, argv PTSRMLS_CC);
	zend_first_try {
		PG(during_request_startup) = 0;
		
		/* We are embeded */
		zend_eval_string("define('EMBEDED', 1);", &ret_value, "main" TSRMLS_CC);

		/* Execute */
		zend_eval_string(eval_string, &ret_value, "main" TSRMLS_CC);

		exit_status= Z_LVAL(ret_value);
	}
	zend_catch {
		exit_status = EG(exit_status);
	}
	zend_end_try();
	php_embed_shutdown(TSRMLS_C);

	return exit_status;
}
