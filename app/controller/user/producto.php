<?php
include_once  "./app/controller/controller.php";
include_once  "./app/model/producto.php";
include_once  "./app/model/categoria.php";

class producto extends Controller{
    
    private $view;
    private $productoModel;
    private $categoria;
    private $num_productos_pagina;
    
    public function __construct(){
        $this->productoModel=new ProductoModel();
        $this->categoriaModel=new categoriaModel();
        $this->num_productos_pagina=3;
        $this->view=$this->getTemplate("./app/views/user/index.html");
    }
    

    public function cargarVistaDetalleProducto($id_producto){
        $contenido= $this->getTemplate("./app/views/user/producto/ver-producto.html");
        $formCarrito= $this->getTemplate("./app/views/user/producto/components/form-carrito.html");
        //aca llamamos al metodo cargarCategoriasMenuLeft que se encargara de crear el html para las categorias
        //del menu que se encuentra en la parte izquierda de la pagina
        $contenido= $this->renderView($contenido, "{{CATEGORIAS_MENU_LEFT}}",$this->cargarCategoriasMenuLeft());
        //consulto todos los productos de la base de datos
        $producto=$this->productoModel->consultarProducto($id_producto);
        $categorias=$producto['categorias'];
        $producto=$producto['infoP'];

        //validamos si existe una sesion iniciada para habilitar el boton de agregar al carrito
        if($this->sesionIniciadaUser()){
           $formCarrito= $this->renderView($formCarrito, "{{ID_PRODUCTO}}",$id_producto); 
           $formCarrito= $this->renderView($formCarrito, "{{CANT_DISPONIBLE}}",$producto['cant_disponibles']);
           $contenido= $this->renderView($contenido, "{{FORM_CARRITO}}",$formCarrito);
        }else{
            $contenido= $this->renderView($contenido, "{{FORM_CARRITO}}",""); 
        }
        
        //cargamos toda la informacion del producto en el html
        $contenido= $this->renderView($contenido, "{{ID_PRODUCTO}}",$id_producto);
        $contenido= $this->renderView($contenido, "{{NOMBRE_PRODUCTO}}",$producto['nombre_producto']);
        $contenido= $this->renderView($contenido, "{{REFERENCIA}}",$producto['referencia']);
        $contenido= $this->renderView($contenido, "{{PRECIO}}",$producto['precio']);
        $contenido= $this->renderView($contenido, "{{CANT_DISPONIBLE}}",$producto['cant_disponibles']);
        $contenido= $this->renderView($contenido, "{{DESCRIPCION}}",$producto['descripcion']);

        //verificamos las url de las imagenes 
        $htmlImages="";
        if($producto['url_img1']!=""){
            $htmlImages.='<a href="'.$producto['url_img1'].'"><img  src="'.$producto['url_img1'].'" alt=""></a>';
        }

        if($producto['url_img2']!=""){
            $htmlImages.='<a href="'.$producto['url_img2'].'"><img  src="'.$producto['url_img2'].'" alt=""></a>';
        }

        if($producto['url_img3']!=""){
            $htmlImages.='<a href="'.$producto['url_img3'].'"><img  src="'.$producto['url_img3'].'" alt=""></a>';
        }
        //cargamos las imagenes al html principal
         $contenido= $this->renderView($contenido, "{{IMAGENES}}",$htmlImages);

        //cargamos las categorias asociadas
        $htmlCategorias="";
        foreach($categorias as $element){
            $nomCategoria=$this->categoriaModel->buscarNombreCategoria($element);
            $htmlCategorias.="<li>$nomCategoria</li>";
        }
        $contenido= $this->renderView($contenido, "{{CATEGORIAS}}",$htmlCategorias);
        //cargamos  los menus de la pagina
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Detalle Producto");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);    
    }
    

    public function cargarVistaProductosCategoria($id_categoria){
         $contenido= $this->getTemplate("./app/views/user/producto/vista-productos.html");
        //aca llamamos al metodo cargarCategoriasMenuLeft que se encargara de crear el html para las categorias
        //del menu que se encuentra en la parte izquierda de la pagina
        $contenido= $this->renderView($contenido, "{{CATEGORIAS_MENU_LEFT}}",$this->cargarCategoriasMenuLeft());
        $contenido= $this->renderView($contenido, "{{ID_CATEGORIA}}",$id_categoria);

        
        //definimos la paginacion en html 
        $total_productos=$this->productoModel->obtenerNumeroTotalProductosCategoria($id_categoria);
        $totalPaginas=ceil($total_productos/$this->num_productos_pagina);
        $contenido= $this->renderView($contenido, "{{TOTAL_PAGINAS}}",$totalPaginas);
        //cargamos  los menus de la pagina
        $this->view=$this->cargarContenidoPlantilla($this->view);
        $this->view = $this->renderView($this->view, "{{TITULO}}","Productos");
        $this->view = $this->renderView($this->view, "{{CONTENT}}", $contenido);
        $this->showView($this->view);    
    }

    public function cargarProductosCategoriaPagina($id_categoria, $pagina){

        $inicio=($pagina-1)*$this->num_productos_pagina;
        $arrayProductos=$this->productoModel->listarProductoCategoriaPorPaginas($id_categoria,$inicio, $this->num_productos_pagina);

         $htmlProductos="";
        foreach ($arrayProductos as $element) {
            $producto=$this->getTemplate("./app/views/user/components/sliderProducto/producto-mas-vendido.html");
             $producto = $this->renderView($producto, "{{PRECIO}}",$element['precio']);
             $producto = $this->renderView($producto, "{{NOMBRE_PRODUCTO}}",$element['nombre_producto']);     
             $producto = $this->renderView($producto, "{{ID_PRODUCTO}}",$element['id_producto']);
             $producto = $this->renderView($producto, "{{URL_IMG}}",$element['url_img1']);
             $htmlProductos.=$producto; 
        }

       echo $htmlProductos;

    }
    
}
?>