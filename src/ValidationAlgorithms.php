<?php namespace Masterkey\ValidDocs;

    /**
     * ValidationAlgorithms
     *
     * Realiza a validação de diversos tipos de documentos
     *
     * @author  Matheus Lopes Santos <fale_com_lopez@hotmail.com>
     * @version 1.0.0
     * @since   08/07/2016
     */
    class ValidationAlgorithms
    {
        /**
         * Recebe a string contendo o número do documento
         *
         * @var string
         */
        protected $valor;

        /**
         * COnstrutor da classe
         */
        public function __construct($documento)
        {
            $this->valor = $documento;
        }

        /**
         * Realiza o cálculo das posições dos valores de cada dígito do documento
         *
         * @param   string  $digitos        Os dígitos desejados
         * @param   integer $posicoes       A posição de onde o algo inicia a regreção
         * @param   integer $soma_digitos   A soma dos multiplicadores entre posições e dígitos
         * @return  string
         */
        public function calcDigitPositions($digitos, $posicoes = 10, $soma_digitos = 0)
        {
    		// Faz a soma dos dígitos com a posição
    		// Ex. para 10 posições:
    		//   0    2    5    4    6    2    8    8   4
    		// x10   x9   x8   x7   x6   x5   x4   x3  x2
    		//   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
    		for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
    			// Preenche a soma com o dígito vezes a posição
    			$soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );

    			$posicoes--;

    			// Parte específica para CNPJ
    			// Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
    			if ( $posicoes < 2 ) {
    				// Retorno a posição para 9
    				$posicoes = 9;
    			}
    		}

    		// Captura o resto da divisão entre $soma_digitos dividido por 11
    		// Ex.: 196 % 11 = 9
    		$soma_digitos = $soma_digitos % 11;

    		// Verifica se $soma_digitos é menor que 2
    		if ( $soma_digitos < 2 ) {
    			// $soma_digitos agora será zero
    			$soma_digitos = 0;
    		} else {
    			// Se for maior que 2, o resultado é 11 menos $soma_digitos
    			// Ex.: 11 - 9 = 2
    			// Nosso dígito procurado é 2
    			$soma_digitos = 11 - $soma_digitos;
    		}

    		// Concatena mais um dígito aos primeiro nove dígitos
    		// Ex.: 025462884 + 2 = 0254628842
    		$cpf = $digitos . $soma_digitos;

    		// Retorna
    		return $cpf;
    	}

        /**
         * Verifica a igualdade dos caracteres
         *
         * @return  bool
         */
        public function verifyEquality() {
            // Todos os caracteres em um array
            $caracteres = str_split($this->valor);

            // Considera que todos os números são iguais
            $todos_iguais = true;

            // Primeiro caractere
            $last_val = $caracteres[0];

            // Verifica todos os caracteres para detectar diferença
            foreach( $caracteres as $val ) {

                // Se o último valor for diferente do anterior, já temos
                // um número diferente no CPF ou CNPJ
                if ( $last_val != $val ) {
                   $todos_iguais = false;
                }

                // Grava o último número checado
                $last_val = $val;
            }

            // Retorna true para todos os números iguais
            // ou falso para todos os números diferentes
            return $todos_iguais;
        }
    }
