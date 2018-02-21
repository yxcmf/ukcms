<?php

//应用需要的行为和对应绑定的钩子
return[
    "behavior" => ["app\member\behavior\memberinfo"],
    "hookBehavior" => [
        ["home_begin","app\member\behavior\memberinfo",1]
    ]
];
