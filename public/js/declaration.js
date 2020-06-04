/**
 * ----------------------------------------
 *              CONSIDERACIONES
 * ---------------------------------------- */
/**
 * Las entidades nombradas a continuación tienen referencia con una tabla de la BASE DE DATOS.
 * Respetar el nombre de las columnas
 */
const ENTIDADES = {
    
    slider: {
        //TABLE: "sliders",
        ATRIBUTOS: {
            order:      {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"orden"},
            image:      {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Archivo - 1350px X 718px",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"250px"},
            section:    {TIPO:"TP_STRING",VISIBILIDAD:"TP_INVISIBLE",NOMBRE:"sección"},
            text:      {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        
        FORM: [
            {
                '/section/<div class="col-12 col-md-8">/text/</div><div class="col-12 col-md-4"><div class="row d-flex justify-content-center"><div class="col-md-6 mb-3">/order/</div><div class="col-12">/image/</div></div>':['section','order','text','image'],
            },
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        },
        EDITOR: {
            text: {
                toolbarGroups: [
                    { name: 'document', groups : [ 'mode' , 'document' , 'doctools' ] },
                    { name: 'basicstyles', groups : [ 'basicstyles' ] },
                    { name: 'clipboard', groups : [ 'clipboard' , 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor' , 'BGColor' ] },
                ],
                removeButtons: 'Save,NewPage,Print,Preview,Templates'
            }
        }
    },
    home_icono: {
        ATRIBUTOS: {
            image: {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"110px X 110px",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"110px"},
            text: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        FORM: [
            {
                '<div class="col-12 col-md-8">/text/</div><div class="col-12 col-md-4">/image/</div>' : ['text','image']
            }
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        },
        EDITOR: {
            text: {
                toolbarGroups: [
                    { name: "basicstyles", groups: [ "basicstyles" ] },
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                ]
            }
        }
    },
    /**
     * 
     */
    empresa: {
        ATRIBUTOS: {
            image: {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"561px X 365px",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"561px"},
            background: {TIPO:"TP_IMAGE",NECESARIO:1,FIELDSET:1,VALID:"Archivo seleccionado",INVALID:"1350px X 537px",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen de fondo",WIDTH:"100%"},
            text:      {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"},
        },
        FORM: [
            {
                '<div class="col-12 col-md-8">/text/</div><div class="col-12 col-md-4">/image/</div>' : ['text','image']
            },
            {
                '<div class="col-12 col-md-9">/background/</div>' : ['background']
            }
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
            background: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        },
        EDITOR: {
            text: {
                toolbarGroups: [{
                    "name": "basicstyles",
                    "groups": ["basicstyles"]
                },
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'links' },
                { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
            ]
            }
        }
    },
    /**
     * DESCARGAS
     */
    descarga: {
        TABLE: "descargas",
        ATRIBUTOS: {
            order:      {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"orden"},
            name:      {TIPO:"TP_STRING",MAXLENGTH:70,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"nombre"},
            cover:      {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"225px X 216px",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"225px"},
            type:   {TIPO:"TP_ENUM",ENUM:{"público":"Público","privado":"Privado"},NECESARIO:1,VISIBILIDAD:"TP_INVISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0 text-uppercase",NOMBRE:"TIPO",COMUN:1},
            category:   {TIPO:"TP_ENUM",ENUM:{"folletos":"Folletos","manual de instalación":"Manual de instalación","órdenes de fabricación":"Órdenes de fabricación"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0 text-uppercase",NOMBRE:"categoría",COMUN:1},
        },
        FORM: [
            {
                '/type/<div class="col-12 col-md-4 col-lg-3">/order/</div>' : ['type','order']
            },
            {
                '<div class="col-12 col-md-6">/cover/</div>' : ['cover']
            },
            {
                '<div class="col-12 col-md-6">/name/</div>' : ['name']
            },
            {
                '<div class="col-12 col-md-6">/category/</div>' : ['category']
            }
        ],
        FUNCIONES: {
            cover: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
        }
    },
    descargap: {
        TABLE: "descargas",
        ATRIBUTOS: {
            order:      {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"orden"},
            name:      {TIPO:"TP_STRING",MAXLENGTH:70,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"nombre"},
            cover:      {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"225px X 216px",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"225px"},
            type:   {TIPO:"TP_ENUM",ENUM:{"público":"Público","privado":"Privado"},NECESARIO:1,VISIBILIDAD:"TP_INVISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0 text-uppercase",NOMBRE:"TIPO",COMUN:1},
            section:   {TIPO:"TP_ENUM",ENUM:{"final":"Final","comercio":"Comercio","mayorista":"Mayorista"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0 text-uppercase",NOMBRE:"tipo",COMUN:1},
        },
        FORM: [
            {
                '/type/<div class="col-12 col-md-4 col-lg-3">/order/</div>' : ['type','order']
            },
            {
                '<div class="col-12 col-md-6">/cover/</div>' : ['cover']
            },
            {
                '<div class="col-12 col-md-6">/name/</div>' : ['name']
            },
            {
                '<div class="col-12 col-md-6">/section/</div>' : ['section']
            }
        ],
        FUNCIONES: {
            cover: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
        }
    },
    descargas: {
        TABLE: "descargapartes",
        ATRIBUTOS: {
            order:      {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"orden"},
            name:      {TIPO:"TP_STRING",MAXLENGTH:70,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"nombre"},
            file:      {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Seleccione ficha",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/jpeg,application/pdf",NOMBRE:"Archivo"},
            descarga_id:      {TIPO:"TP_ENUM",RELACION:"descargas",RELACIONNAME:"name",VISIBILIDAD:"TP_INVISIBLE",ENUMOP:"descargas",NOMBRE:"Descarga",COMUN:1}
        },
        FORM: [
            {
                '/descarga_id/<div class="col-12 col-md-4 col-lg-3">/order/</div>' : ['descarga_id','order']
            },
            {
                '<div class="col-12 col-md-6">/file/</div>' : ['file']
            },
            {
                '<div class="col-12 col-md-6">/name/</div>' : ['name']
            },
        ],
    },
    /**
     * PRODUCTOS
     */
    familia: {
        TABLE: "familias",
        ATRIBUTOS: {
            order:      {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"orden"},
            image:      {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"301px X 287px",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"301px"},
            name:      {TIPO:"TP_STRING",MAXLENGTH:70,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"nombre"},
        },
        FORM: [
            {
                '<div class="col-12 col-md-4 col-lg-3">/order/</div>' : ['order']
            },
            {
                '<div class="col-12 col-md-6">/image/</div>' : ['image']
            },
            {
                '<div class="col-12 col-md-6">/name/</div>' : ['name']
            }
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
        }
    },
    categoria: {
        TABLE: "categorias",
        ATRIBUTOS: {
            order:      {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"orden"},
            image:      {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"301px X 287px",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"301px"},
            name:      {TIPO:"TP_STRING",MAXLENGTH:70,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"nombre"},
            categoria_id:      {TIPO:"TP_ENUM",RELACION:"familias",RELACIONNAME:"name",VISIBILIDAD:"TP_INVISIBLE",ENUMOP:"familias",NOMBRE:"Familia",COMUN:1}
        },
        FORM: [
            {
                '/categoria_id/<div class="col-12 col-md-4 col-lg-3">/order/</div>' : ['order','categoria_id']
            },
            {
                '<div class="col-12 col-md-6">/image/</div>' : ['image']
            },
            {
                '<div class="col-12 col-md-6">/name/</div>' : ['name']
            }
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
        }
    },
    producto: {
        TABLE: "productos",
        ATRIBUTOS: {
            order:      {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"orden"},
            name:      {TIPO:"TP_STRING",MAXLENGTH:90,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"nombre"},
            code:      {TIPO:"TP_STRING",MAXLENGTH:10,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",NOMBRE:"código"},
            file:      {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Seleccione ficha",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/jpeg,application/pdf",NOMBRE:"ficha técnica"},
            charact:      {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE_FORM",FIELDSET:1,NOMBRE:"características"},
            categoria_id:      {TIPO:"TP_ENUM",RELACION:"familias",RELACIONNAME:"name",VISIBILIDAD:"TP_INVISIBLE",ENUMOP:"familias",NOMBRE:"Familia",COMUN:1}
        },
        FORM: [
            {
                '<div class="col-12 col-md-3">/categoria_id/</div><div class="col-12 col-md-3">/code/</div><div class="col-12 col-md-3 col-lg-3">/order/</div>' : ['order','code','categoria_id']
            },
            {
                '<div class="col-12 col-md-6">/name/</div>' : ['name']
            },
            {
                '<div class="col-12 col-md-6">/file/</div>' : ['file']
            },
            {
                '<div class="col-12">/charact/</div>' : ['charact']
            }
        ],
        EDITOR: {
            charact: {
                toolbarGroups: [
                    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                    {
                        "name": "basicstyles",
                        "groups": ["basicstyles"]
                    },
                    { name: 'clipboard', groups: [ 'clipboard', 'undo' ]},
                    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                    { name: 'links'},
    
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'links' },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                ],
                removeButtons: 'CreateDiv,Language,Save,NewPage,Preview,Print,Templates'
            }
        }
    },
    productos: {
        TABLE: "productos",
        ATRIBUTOS: {
            order:      {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"orden"},
            name:      {TIPO:"TP_STRING",MAXLENGTH:90,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"nombre"},
            code:      {TIPO:"TP_STRING",MAXLENGTH:10,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",NOMBRE:"código"},
            file:      {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Seleccione ficha",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/jpeg,application/pdf",NOMBRE:"ficha técnica"},
            charact:      {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE_FORM",FIELDSET:1,NOMBRE:"características"},
            categoria_id:      {TIPO:"TP_ENUM",RELACION:"categorías",RELACIONNAME:"name",VISIBILIDAD:"TP_VISIBLE",ENUMOP:"categorias",NOMBRE:"Categoría",COMUN:1,CLASS:"border-left-0 border-right-0 border-top-0 rounded-0"}
        },
        FORM: [
            {
                '<div class="col-12">/categoria_id/</div>':['categoria_id']
            },
            {
                '<div class="col-12 col-md-3">/code/</div><div class="col-12 col-md-3 col-lg-3">/order/</div>' : ['order','code','categoria_id']
            },
            {
                '<div class="col-12 col-md-6">/name/</div>' : ['name']
            },
            {
                '<div class="col-12 col-md-6">/file/</div>' : ['file']
            },
            {
                '<div class="col-12">/charact/</div>' : ['charact']
            }
        ]
    },
    productoimage: {
        //TABLE: "categorias",
        ATRIBUTOS: {
            order:      {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center border-left-0 border-right-0 border-top-0 rounded-0",WIDTH:"150px",NOMBRE:"orden"},
            image:      {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"438px X 345px",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"438px"},
            producto_id:      {TIPO:"TP_ENUM",RELACION:"productos",RELACIONNAME:"name",VISIBILIDAD:"TP_INVISIBLE",ENUMOP:"productos",NOMBRE:"Producto",COMUN:1}
        },
        FORM: [
            {
                '/producto_id/<div class="col-12 col-md-4 col-lg-3">/order/</div>' : ['order','producto_id']
            },
            {
                '<div class="col-12 col-md-6">/image/</div>' : ['image']
            }
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
        }
    },

    /**
     * CLIENTES
     */
    cliente: {
        TABLE: "clientes",
        ATRIBUTOS: {
            name:      {TIPO:"TP_STRING",MAXLENGTH:100,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",LABEL:1,NOMBRE:"nombre"},
            username:      {TIPO:"TP_STRING",MAXLENGTH:40,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",LABEL:1,NOMBRE:"usuario"},
            type:      {TIPO:"TP_ENUM",ENUM:{"final":"Final","comercio":"Comercio","mayorista":"Mayorista"},VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",NOMBRE:"tipo",LABEL:1,COMUN:1},
            password:      {TIPO:"TP_PASSWORD",VISIBILIDAD:"TP_VISIBLE_FORM",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",NOMBRE:"Contraseña",LABEL:1},
            email:      {TIPO:"TP_EMAIL",MAXLENGTH:200,VISIBILIDAD:"TP_VISIBLE",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0",NOMBRE:"email",LABEL:1},
        },
        
        FORM: [
            {
                '<div class="col-12">/name/</div>':['name'],
                '<div class="col-12 col-md-4">/username/</div><div class="col-12 col-md-4">/password/</div><div class="col-12 col-md-4">/type/</div>':['username','password','type'],
                '<div class="col-12 col-md-8">/email/</div>':['email'],
            },
        ]
    },
    /** DATOS de la EMPRESA */
    usuarios: {
        ATRIBUTOS: {
            username: {TIPO:"TP_STRING",MAXLENGTH:30,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"usuario",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0"},
            name: {TIPO:"TP_STRING",MAXLENGTH:100,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"nombre",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0"},
            password: {TIPO:"TP_PASSWORD",VISIBILIDAD:"TP_VISIBLE_FORM",NOMBRE:"contraseña",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0"},
            is_admin: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",ENUM:{1:"Administrador",0:"Usuario"},NOMBRE:"Tipo",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0 text-uppercase",COMUN:1, NECESARIO: 1},
        },
        FORM: [
            {
                '<div class="col-12 col-md-4 col-lg-3">/BTN/</div>' : ['BTN']
            },
            {
                '<div class="col-12 col-md-6">/is_admin/</div>' : ['is_admin']
            },
            {
                '<div class="col-12 col-md-6">/name/</div>' : ['name']
            },
            {
                '<div class="col-12 col-md-3">/username/</div><div class="col-12 col-md-3">/password/</div>' : ['username','password']
            }
        ],
        BTN: {
            "icono": '<i class="fas fa-save ml-2"></i>',
            "titulo": '',
            "mayuscula": 1,
            "class": "btn btn-success btn-block text-uppercase"
        }
    },
    terminos: {
        ATRIBUTOS: {
            titulo: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título",CLASS:"border-left-0 border-right-0 border-top-0 rounded-0"},
            texto: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        FORM: [
            {
                '<div class="col-12">/titulo/</div><div class="col-12 mt-2">/texto/</div>' : ['titulo','texto']
            }
        ]
    },
    metadatos: {
        ATRIBUTOS: {
            seccion: {TIPO:"TP_ENUM",ENUM:{index:"Inicio",home:"Home",empresa:"Empresa",productos:"Productos",servicios:"Servicios",postventa:"Post-Venta",galeria:"Galería",clientes:"Clientes",contacto: "Contacto"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_TABLE",CLASS:"text-uppercase",NOMBRE:"sección",WIDTH:"150px"},
            metas: {TIPO:"TP_TEXT",VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"metadatos (,)", CLASS:"rounded-0"},
            descripcion: {TIPO:"TP_TEXT",VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"descripción", CLASS:"rounded-0"}
        },
        FORM: [
            {
                '/seccion/<div class="d-flex col-3 col-md-3">/BTN/</div>' : ['seccion','BTN']
            },
            {
                '<div class="col-12">/descripcion/</div><div class="col-12 mt-2">/metas/</div>' : ['descripcion','metas']
            }
        ]
    },
    redes: {
        ATRIBUTOS: {
            redes: {TIPO:"TP_ENUM",ENUM:{facebook:'Facebook',instagram:'Instagram',twitter:'Twitter',youtube:'YouTube',linkedin:'LinkedIn'},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase",NOMBRE:"red social",COMUN:1},
            titulo: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            url: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"link del sitio"},
        },
        FORM: [
            {
                '<div class="col-12 col-md-6">/redes/</div><div class="d-flex col-12 col-md-4 col-lg-3">/BTN/</div>' : ['redes','BTN']
            },
            {
                '<div class="col-12 col-md-6">/titulo/</div><div class="col-12 col-md-6">/url/</div>' : ['titulo','url']
            }
        ],
        BTN: {
            "icono": '<i class="fas fa-save ml-2"></i>',
            "titulo": '',
            "mayuscula": 1,
            "class": "btn btn-success btn-block text-uppercase"
        }
    },
    
    empresa_email: {
        ATRIBUTOS: {
            email: {TIPO:"TP_EMAIL",MAXLENGTH:150,VISIBILIDAD:"TP_VISIBLE",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0"}
        },
        FORM: [
            {
                '<div class="col-12">/email/</div>' : ['email']
            }
        ]
    },
    empresa_telefono: {
        ATRIBUTOS: {
            telefono: {TIPO:"TP_PHONE",MAXLENGTH:50,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"teléfono",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0",HELP:"Contenido oculto en el HREF"},
            tipo: {TIPO:"TP_ENUM",ENUM:{tel:"Teléfono",wha:"Whatsapp"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_FORM",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0 text-uppercase",NOMBRE:"Tipo",COMUN: 1},
            visible: {TIPO:"TP_STRING",MAXLENGTH:50,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"elemento visible",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0",HELP:"Contenido visible. En caso de permanecer vacío, se utilizará el primer campo"},
            is_link: {TIPO:"TP_CHECK",VISIBILIDAD:"TP_VISIBLE",CHECK:"¿Es clickeable?"},
        },
        FORM: [
            {
                '<div class="col-12 col-md-6">/tipo/</div><div class="col-12 mt-3">/telefono/</div>' : ['tipo','telefono']
            },
            {
                '<div class="col-12">/visible/</div>':['visible']
            },
            {
                '<div class="col-12 col-md-6">/is_link/</div>':['is_link']
            }
        ]
    },
    empresa_domicilio: {
        ATRIBUTOS: {
            calle: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0"},
            altura: {TIPO:"TP_ENTERO",VISIBILIDAD:"TP_VISIBLE",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0"},
            localidad: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"localidad",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0"},
            provincia: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0"},
            pais: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"país",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0"},
            cp: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"código postal",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0"},
            mapa: {TIPO:"TP_TEXT",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"ubicación de Google Maps",CLASS:"bg-transparent border-top-0 border-left-0 border-right-0 rounded-0"}
            //mapa: {TIPO:"TP_TEXT",VISIBILIDAD:"TP_VISIBLE",:"TP_VISIBILE"}
        },
        FORM: [
            {
                '<div class="col-12 col-md-8">/calle/</div><div class="col-12 col-md-4">/altura/</div>' : ['calle','altura'],
            },
            {
                '<div class="col-12 col-md-6">/cp/</div><div class="col-12 col-md-6">/pais/</div>' : ['cp','pais']
            },
            {
                '<div class="col-12 col-md-6">/provincia/</div><div class="col-12 col-md-6">/localidad/</div>' : ['provincia','localidad']
            },
            {
                '<div class="col-12"><div class="alert alert-primary" role="alert">Copie de <a href="https://www.google.com/maps" target="blank"><strong>Google Maps</strong> <i class="fas fa-external-link-alt"></i></a> la ubicación de la Empresa <i class="fas fa-share-alt"></i> / Insertar mapa / iFrame sin width y height</div>/mapa/</div>' : ['mapa']
            }
        ]
    },
    empresa_images: {
        ATRIBUTOS: {
            logo: {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Logotipo OK",INVALID:"Logotipo - 178x72",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"178px"},
            logoFooter: {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Logotipo Footer OK",INVALID:"Logotipo Footer - 178x72",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"178px"},
            favicon: {TIPO:"TP_IMAGE",NECESARIO:1,VALID:"Favicon OK",INVALID:"Favicon",BROWSER:"",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/x-icon,image/png",NOMBRE:"imagen",WIDTH:"70px"},
        },
        FORM: [
            {
                '<div class="col-7 col-md-5">/logo/</div><div class="col-5 col-md-5">/logoFooter/</div><div class="col-3 col-md-2">/favicon/</div>' : ['logo','logoFooter','favicon']
            }
        ],
        FUNCIONES: {
            logo: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
            logoFooter: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
            favicon: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        }
    }
};