<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-16">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-3">
                                <h5 class="float-start">Listado de Productos</h5>
                            </div>
                            <div class="input-group rounded col-md-4 col-sm-12">
                          <input type="search" v-model="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon">
                        </div>
                            <div class="col-3">
                                <button @click="showDialog" class="btn btn-success btn-sm float-end">Nuevo</button>
                            </div>
                            <button type="button" class="btn btn-secondary" @click="alternarMostrarProductos">
      {{ mostrarInactivos ? 'Mostrar Activos' : 'Mostrar Inactivos' }}
    </button>

                            <table class="table bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Sabor</th>
                                    <th scope="col">Relleno</th>
                                    <th scope="col">Catalogo</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Imagen</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                <tbody>
                    <tr v-for="item in productosMostrados.filter(item => item.nombre.toLowerCase().includes(search.toLowerCase()))" :key="item.id">
                        <!-- Celdas de la tabla para productos activos -->
                        <td>{{ item.nombre }}</td>
                                    <td>{{ item.descripcion }}</td>
                                    <td>
                                        <span v-for="sabor in item.sabores">{{ sabor.nombre }}</span>
                                    </td>
                                    <td>{{ item.relleno && item.relleno.nombre ? item.relleno.nombre : '-' }}</td>
                                    <td>{{ item.catalogo && item.catalogo.nombre ? item.catalogo.nombre : '-' }}</td>
                                    <td>{{ item.precio }}</td>
                                    <td><img :src="`/images/productos/${item.imagen}`" :alt="`${item.imagen}`" style="width:100px;height: 100px"></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" @click="editarProducto(item)">Editar</button>
                                            &nbsp;
                                            <button  type="button" class="btn btn-danger btn-sm" v-if="!mostrarInactivos" @click="desactivarProducto(item.id)">Desactivar</button>
                                    &nbsp;
                                    <button type="button" class="btn btn-danger btn-sm" v-else @click="activarProducto(item.id)">Activar</button>
                                 </td>
                            </tr>
                         </tbody>
                       </table>
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-7" id="productoModalLabel">{{ formTitle }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Definiendo el cuerpo del formulario modal -->
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" v-model="producto.nombre">
                            <span class="text-danger" v-show="productoErrors.nombre">nombre requerido</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="descripcion">Descripcion</label>
                            <input type="text" class="form-control" v-model="producto.descripcion">

                        </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-6">
                          <label for="sabores">Sabores</label>
                          <select v-model="producto.saboresSeleccionados" class="form-control">
                            <input type="text" class="form-control" v-model="producto.saboresAsociados" disabled>
                           <option v-for="sabor in sabores" :value="sabor.id">{{ sabor.nombre }}</option>
                        </select>
                       <span class="text-danger" v-show="productoErrors.sabor">Seleccione al menos un sabor</span>
                    </div>
                    <div class="form-group col-6">
                     <label for="saboresAsociados">Sabor</label>
                      <input type="text" class="form-control" v-model="producto.saboresAsociados" disabled>
                    </div>


                        <div class="form-group col-6">
                            <label for="relleno">Relleno</label>
                            <select v-model="producto.relleno_id" class="form-control">
                                <option v-for="relle in rellenos" :value="relle.id">
                                    {{ relle.nombre }}
                                </option>
                            </select>
                        </div>
                    </div>


                    <div class="row">
                        <div class="form-group col-6">
                            <label for="precio">Precio</label>
                            <input type="text" class="form-control" v-model="producto.precio" />
                            <span class="text-danger" v-show="productoErrors.precio">precio es requerido</span>
                        </div>
                    </div>
                    <div class="row">
                      <div class="form-group col-6">
                            <label for="catalogo">Catalogo</label>
                            <select v-model="producto.catalogo_id" class="form-control">
                                <option v-for="catalogo in catalogos" :value="catalogo.id" >
                                {{ catalogo.nombre }}
                                </option>
                            </select>
                            <span class="text-danger" v-show="productoErrors.sabor">Seleccione un Catalogo</span>
                        </div>
                        <div class="row">
                    </div>
                    </div>
                    <div class="row">
                      <div class="col-6">
                          <label for="formfile" class="form-label">Seleccione una imagen para el producto</label>
                          <input type="file" class="form-control" accept="image/*" @change="getImage">
                      </div>
                      <div class="col6">
                          <figure>
                              <img v-if="imagePreview" :src="imagePreview" width="200" height="150"/>
                          </figure>
                      </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cerrar
                    </button>
                  <button type="button" class="btn btn-primary" @click="saveOrUpdate()">
                        {{ btnTitle }}
                    </button>
                </div>
                </div>
            </div>
        </div>
  </div>
  </template>

  <script>
   import Swal from 'sweetalert2';
  export default {
    data() {
        return {
            productos: [],
            producto: {
                id: null,
                nombre: "",
                descripcion:null,
                sabor_id: null,
                relleno_id:null,
                precio: 0,
                catalogo_id:null,
                imagen: "",
                sabor:  null,
                relleno: null,
                catalogo:null,
                saboresSeleccionados: []

            },
           imageCar:null,
            imagePreview:null,
            editedProducto: -1,
            productoErrors: {
                nombre:false,
                sabor:false,
                relleno: false,
                precio:false,
                catalogo:false,
            },
            filters:[],
            search:'',
            sabores:[],
            producto_sabores: [],
            rellenos:[],
            catalogos:[],
      productosActivos: [],
      productosInactivos: [],
      productosMostrados: [],
      mostrarInactivos: false
        }
    },  
    computed:{
        formTitle(){
            return this.producto.id == null ? "Agregar producto" : "Actualizar producto";
          },
          btnTitle(){
          return this.producto.id == null ? "Guardar" : "Actualizar";
          },
        },
    methods: {

async fetchProductos() {
        let me = this;
        try {
            const response = await this.axios.get('/productos');
            // Verificar si la respuesta tiene las propiedades esperadas
            if (response.data.productos_activos && response.data.productos_inactivos) {
                // Asignar las propiedades a las variables correspondientes
                me.productosActivos = response.data.productos_activos;
                me.productosInactivos = response.data.productos_inactivos;
               

                 // Actualizar la información de sabores asociados
            me.productosActivos.forEach(producto => {
                producto.saboresAsociados = producto.sabores.map(sabor => sabor.nombre).join(', ');
            });

            me.productosInactivos.forEach(producto => {
                producto.saboresAsociados = producto.sabores.map(sabor => sabor.nombre).join(', ');
            });
            
            me.productosMostrados = me.mostrarInactivos ? me.productosInactivos : me.productosActivos;
                // Agrega un console.log para depurar
        this.productosMostrados.forEach(producto => {
          console.log('Producto:', producto);
        });
      } else {
        console.error('La respuesta de /productos no tiene las propiedades esperadas:', response.data);
      }
    } catch (error) {
      console.error('Error al obtener productos:', error);
    }
},

obtenerNombreSaborPorId(saborId) {
        const sabor = this.sabores.find(sabor => sabor.id === saborId);
        return sabor ? sabor.nombre : '';
    },

        async fetchSabores(){
                  let me = this;
                  await this.axios.get('/sabores')
                  .then(response =>{
                     me.sabores = response.data;
                     console.log('Sabores:', me.sabores);
                  })
              },

        async fetchRellenos() {
            let me = this;
            await this.axios.get('/rellenos')
                .then(response => {
                    me.rellenos = response.data;
                })
        },
        async fetchCatalogos(){
                  let me = this;
                  await this.axios.get('/catalogos')
                  .then(response =>{
                     me.catalogos = response.data;
                  })
              },
              
       async desactivarProducto(id) {
      try {
        const response = await this.axios.put(`/productos/${id}/desactivar`);
        // Manejar la respuesta según tus necesidades
        console.log(response.data.message);
        // Puedes actualizar la lista de productos después de desactivar
        this.fetchProductos();
      } catch (error) {
        console.error('Error al desactivar el producto:', error);
        // Manejar errores, mostrar mensajes, etc.
      }
      this.mostrarSweetAlert('Producto desactivado exitosamente');
    },
    async activarProducto(id) {
      try {
        const response = await this.axios.put(`/productos/${id}/activar`);
        // Manejar la respuesta según tus necesidades
        console.log(response.data.message);
        // Puedes actualizar la lista de productos después de desactivar
        this.fetchProductos();
      } catch (error) {
        console.error('Error al desactivar el producto:', error);
        // Manejar errores, mostrar mensajes, etc.
      }
      this.mostrarSweetAlert('Producto activado exitosamente');
    },
    alternarMostrarProductos() {
      this.mostrarInactivos = !this.mostrarInactivos;
      this.productosMostrados = this.mostrarInactivos
        ? this.productosInactivos
        : this.productosActivos;
    },
    mostrarProductosActivos() {
      this.mostrarInactivos = false;
      this.productosMostrados = this.productosActivos;
    },
    mostrarProductosInactivos() {
      this.mostrarInactivos = true;
      this.productosMostrados = this.productosInactivos;
    },
    obtenerProductos() {
        axios.get('/api/productos') // Ajusta la ruta según tu configuración
    .then(response => {
      // Verificar si la respuesta tiene las propiedades necesarias
      if (response.data && response.data.productos_activos && response.data.productos_inactivos) {
        // Asignar los arrays a las propiedades correspondientes
        this.productosActivos = response.data.productos_activos;
        this.productosInactivos = response.data.productos_inactivos;
        this.productosMostrados = this.mostrarInactivos ? this.productosInactivos : this.productosActivos;

        // Mostrar productos activos por defecto
        this.mostrarProductosActivos();
      } else {
        console.error('La respuesta no tiene la estructura esperada:', response.data);
      }
    })
    .catch(error => {
      console.error('Error al obtener productos:', error);
    });
    },
    mostrarSweetAlert(mensaje) {
      Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: mensaje,
      }).then(() => {
        // Recargar la página después de cerrar la alerta
        location.reload();
      });
    },
    editarProducto(producto) {
      this.showDialogEditar(producto);
    },
        showDialog() {
            this.producto = {
                id: null,
                nombre: "",
                descripcion: "",
                precio: 0,
                imagen: "",
                sabor: null,
                relleno: null,
                catalogo:null,
                sabor_id:null,
                relleno_id:null,
                catalogo_id:null,
                saboresSeleccionados: []
            };
            imageCar:null,
            this.imagePreview = null;
            this.productoErrors = {
                nombre: false,
                descripcion: false,
                precio: false,
                imagen: false,
                sabor: false,
                relleno: false,
                catalogo: false,
            };
            $('#productoModal').modal('show');
        },
        showDialogEditar(producto) {
      $('#productoModal').modal('show');
      if (producto) {
        this.editedProducto = this.productos.indexOf(producto);
        this.producto = {
          id: producto.id,
          nombre: producto.nombre,
          descripcion: producto.descripcion,
          sabor_id: producto.sabores.map(sabor => sabor.id), // Asigna ID de sabores
          saboresSeleccionados: producto.sabores.map(sabor => sabor.id),
          relleno_id: producto.relleno ? producto.relleno.id : null,
          catalogo_id: producto.catalogo ? producto.catalogo.id : null,
          precio: producto.precio,
          imagen: producto.imagen,
        };
        // Obtener nombres de sabores asociados al producto
        this.producto.saboresAsociados = producto.sabores.map(sabor => sabor.nombre).join(', ');
        this.imagePreview = `/images/productos/${this.producto.imagen}`;
      }
    },
        hideDialog() {
            let me = this;
            setTimeout(() => {
                me.producto = {
                id: null,
                nombre: "",
                descripcion: "",
                precio: 0,
                imagen: "",
                sabor: null,
                relleno: null,
                catalogo:null,
                },
                me.imagePreview = null,
                me.imageCar = null
            }, 300);
            $('#productoModal').modal('hide');
        },
        async saveOrUpdate() {
            let me = this;

            me.producto.nombre == '' ? me.productoErrors.nombre = true : me.productoErrors.nombre = false;
            me.producto.descripcion == '' ? me.productoErrors.descripcion = true : me.productoErrors.descripcion = false;
            me.producto.precio == null ? me.productoErrors.precio = true : me.productoErrors.precio = false;
            me.producto.imagen == '' ? me.productoErrors.imagen= true : me.productoErrors.imagen = false;
            me.producto.sabor_id == null ? me.productoErrors.sabor = true : me.productoErrors.sabor = false;
            me.producto.relleno_id == null ? me.productoErrors.relleno = true : me.productoErrors.relleno = false;
            me.producto.catalogo_id == null ? me.productoErrors.catalogo = true : me.productoErrors.catalogo = false;
          
            if (me.producto.nombre) {
                let accion = me.producto.id == null ? "add" : "upd";

                //  me.producto.sabor = {
                //     "id" : me.producto.sabor_id
                // };
                // me.producto.relleno = {
                //     "id" : me.producto.relleno_id
                // };
                // me.producto.catalogo = {
                //     "id" : me.producto.catalogo_id
                // };

                let formData = new FormData();

                formData.append("nombre", me.producto.nombre);
                formData.append("descripcion", me.producto.descripcion);
                formData.append("precio", me.producto.precio);
                formData.append("imagen", me.producto.imagen);
                formData.append("saboresSeleccionados", JSON.stringify(me.producto.saboresSeleccionados));
                formData.append("catalogo_id", me.producto.catalogo_id);

                if (me.producto.relleno_id !== null) {
                    formData.append("relleno_id", me.producto.relleno_id);
                }               
                if(me.imageCar != null)
                {
                  formData.append("imagen", me.imageCar);
                }
                //definiendo encabezado de peticion
                let headers = {
                  headers: {
                      "Content-Type": "multipart/form-data"
                  }
                };
                if (accion == "add") {

                    //peticion para guardar una auto
                    await this.axios.post('/productos', formData, headers) //cambie esto alex
                        .then(response => {
                           // console.log(response.data);
                            if (response.status == 201) {
                                me.verificarAccion(response.data.data,response.status,accion);
                                me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        })
                } else {

                    //peticion para actualizar una marca
                   formData.append("id",me.producto.id);
                   await this.axios
                   .post(`/productos/${me.producto.id}`, formData, headers)
                    .then(response => {
                            if(response.status == 202){
                             me.verificarAccion(
                             response.data.data,
                             response.status,
                             accion
                             );
                             me.hideDialog();
                            }
                        }).catch(errors => {
                            console.log(errors);
                        });

                }
            }
        },
        verificarAccion(producto, statusCode, accion) {
            let me = this;
            const Toast = this.$swal.mixin({
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 2000,
                tinerProgressBar: true
            });
            switch (accion) {
        case "add":
        case "upd":
            // Actualizar la lista de productos
            me.fetchProductos();
            // Mostrar el modal después de agregar o actualizar
            me.showDialog();
            Toast.fire({
                icon: 'success',
                title: `Producto ${accion === 'add' ? 'agregado' : 'actualizado'} con éxito...!`
            });
            break;
                case "del":
                    if (statusCode == 205) {
                        me.productos.splice(this.editedProducto, 1);
                        Toast.fire({
                            icon: 'success',
                            'title': 'Producto eliminada con exito...!'
                        });
                    } else {
                        Toast.fire({
                            icon: 'warning',
                            'title': 'Ocurrió un error, intente de nuevo...!'
                        });
                    }
                    break;
            }
        },


  getImage(event){
      let file = event.target.files[0];
      this.imageCar = file;
      this.loadImage(file);
  },
  loadImage(file)
  {
      let reader = new FileReader();
      reader.onload = (e) => {
          this.imagePreview = e.target.result;
      }
      reader.readAsDataURL(file);
  }
    },
    mounted() {
        // this.$swal('Welcome to RentasCars!!!');
        this.fetchProductos();
        this.fetchSabores();
        this.fetchRellenos();
        this.fetchCatalogos();
        this.obtenerProductos();
       // this.fetchCoberturas()
    }
  }
  </script>

  