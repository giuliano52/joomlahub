PQZ
===
solo csv file
solo ini file

#definizioni 
##variabili 
$configuration : contiene le configurazioni del quiz, viene letta da un file ini o impostata direttamente $this->configuration['parametro']= 'valore'
                i pararametri sono:
                - base_ini_dir  : la directory base dove si trovano i file ini (default data/ini)

               

##funzioni
-getIniConf(inifile)
-changeConf(parametro,valore)
-scanIniDir(dir) -> array [ type (inifile|dir) , name (nome file o nome dir), description (descrione contenuta nell'ini file) ]
-initializeQuiz() -> array con le domande , ma modifica anche la $this->configuration 



#CSV file
##Campi
* sono obbligatori
- id                    : unique id of the question
- question *  			: La domanda come viene presentata
- correct_answer *		: La risposta corretta (in caso di più risposte corrette separale da | ) 
- wrong_answer			: Le verie risposte possibili (separate da | )
- difficult_level		: il livello di difficoltà (0-100) (10 Molto facile, 25 Facile, 50 Medio, 75 Difficle, 90 Molto Difficile)
- response_type			: options (risposta multipla) text (risposta libera) (Si può impostare un valore per includere un massimo numero di opzioni es options_3)
- tags					: categoria della domanda (tags separati da |)
- if_correct			: testo descrittivo che viene mostrato se la risposta è corretta
- if_wrong				: testo descrittivo che viene mostrato se la risposta è sbagliata 



