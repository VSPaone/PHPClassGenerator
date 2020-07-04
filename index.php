<?php
function generateClass() {
    $classes = json_decode(file_get_contents("classes.json"),true);
    foreach($classes as $class) {
        $code = "<?php\n";
        if(isset($class['name'])) {
            $name = ucfirst($class['name']);
            $code .= "class ".$name." {\n";
            if(isset($class['globals'])) {
                $globals = $class['globals'];
                foreach($globals as $global) {
                    $code .= "\t".$global['access'].' $'.$global['name'].";\n";
                }
            }
            if(isset($class['params'])) {
                $params = $class["params"];
                $ps = $params;
                foreach ($ps as &$value) {
                    $value = '$' . $value;
                }
                unset($value);
                $code .= "\tfunction __construct(".implode(',',$ps).") {\n";
                $funcs = "";
                foreach($params as $param) {
                    $code.= "\t\t\$this->".$param." = $".$param.";\n";
                    $funcs .= "\tfunction get".ucfirst($param)."($".$param.") {\n";
                    $funcs .= "\t\treturn \$this->".$param.";\n\t}\n";
                    $funcs .= "\tfunction set".ucfirst($param)."($".$param.",\$value) {\n";
                    $funcs .= "\t\t\$this->".$param." = $".$param.";\n\t}\n";
                }
            }
            $code .= "\t}\n";
            if(isset($class['functions'])) {
                $fs = $class["functions"];
                foreach($fs as $f) {
                    $nm = $f['name'];
                    $params = $f['params'];
                    $ps = $params;
                    foreach ($ps as &$value) {
                        $value = '$' . $value;
                    }
                    unset($value);
                    $funcs .= "\tfunction ".$nm."(".implode(',',$ps).") {\n\t\treturn 1;\n\t}\n";
                }
            }
            $code .= $funcs;
        }
        $code .= "}\n?>";
        if(file_put_contents($name.'.php',$code)>0)
            echo "<p>Class ".$name." is created successfully.</p>";
    }
}
generateClass();
?>