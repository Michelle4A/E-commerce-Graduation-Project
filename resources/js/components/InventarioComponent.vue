<template>
        <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-7">
                        <h5>Listado de Inventario</h5>
                      </div>
                       <div class="col 6">
                        <button @click="showDialog" class="btn btn-success btn-sm float-end">Nuevo</button>
                       </div>
                    </div>
                    <div class="row">
                      <div class="input-group rounded">
                        <input type="search" v-model="search" @input="buscar" class="form-control rounded" placeholder="Buscar" arial-label="Search" aria-describedby="search-addon">
                      </div>
                    </div>
                  
                      </div>
                    <div class="card-body">
                      <table class="table bordered">
                        <thead>
                          <tr>
                            <th scope="col">cantidad en inventario</th>
                            <th scope="col">Producto</th>                            
                            <th scope="col">fecha de agregado</th>
                            <th scope="col">fecha de vencimiento</th>                            
                            <th scope="col">Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in inventarios" :key="item.id">
                                <td>{{ item.cantidad_inventario }}</td>
                                <td>{{ item.producto && item.producto.nombre ? item.producto.nombre : '-' }}</td>
                                <td>{{ item.fecha_agregado }}</td>
                                <th>{{ item.fecha_vencimiento }}</th>
                                <td>
                                  <button class="btn btn-primary btn-sm" @click="showDialogEditar(item)">Editar</button>
                                  &nbsp;
                                  <button class="btn btn-danger btn-sm" @click="eliminar(item)">Eliminar</button>
                                </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <!-- Modal -->
  <div class="modal fade" id="inventarioModal" tabindex="-1" aria-labelledby="inventarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title fs-7" id="inventarioModalLabel">{{ formTitle }}</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
                    <!-- Definiendo el cuerpo del formulario modal -->
                    <div class="row">
           
            <div class="form-group col-6">
              <label for="producto">Producto</label>
              <select v-model="inventario.producto_id" class="form-control">
                <option v-for="producto in productos" :value="producto.id">
                  {{ producto.nombre }}
                </option>
              </select>
              <span class="text-danger" v-show="inventarioErrors.producto">Seleccione un Producto</span>
            </div>
             <div class="form-group col-6">
              <label for="cantidad_inventario">Cantidad</label>
              <input type="number" class="form-control" v-model="inventario.cantidad_inventario">
              <span class="text-danger" v-show="inventarioErrors.cantidad_inventario">La cantidad es requerida</span>
            </div>

                    </div>
                        <div class="row">
                        <div class="form-group col-6">
                            <label for=" fecha_agregado">Fecha de agregado</label>
                            <input type="date" class="form-control" v-model="inventario.fecha_agregado">
                            <span class="text-danger" v-show="inventarioErrors.fecha_agregado">la fecha es requerida</span>
                        </div>
                        <div class="form-group col-6">
                            <label for="fecha_vencimiento">Fecha vencimiento</label>
                            <input type="date" class="form-control" v-model="inventario.fecha_vencimiento">
                            <span class="text-danger" v-show="inventarioErrors.fecha_vencimiento">la fecha es requerida</span>
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
  export default {
    data() {
        return {
            inventarios: [],
            inventario: {
                id: null,
                cantidad_inventario: "",
                fecha_agregado: "",
                fecha_vencimiento:"",
                producto_id: null,
                producto: null
            },
            editedInventario: -1,
            inventarioErrors: {
                cantidad_inventario: "",
                fecha_agregado:false,
                fecha_vencimiento:false,
                producto:false,
            },
            filters:[],
            search:"",
            productos:[]

        }
    },  
    computed:{
        formTitle(){
            return this.inventario.id == null ? "Agregar inventario" : "Actualizar inventario";
          },
          btnTitle(){
          return this.inventario.id == null ? "Guardar" : "Actualizar";
          }
        },
    methods: {

        async fetchInventarios() {
            let me = this;
            await this.axios.get('/inventarios')
                .then(response => {
                    me.inventarios = response.data;
                })
        },
             async fetchProductos() {
    let me = this;
    await this.axios.get('/productInvent')
        .then(response => {
            console.log('Respuesta de /productos:', response.data); // Agrega este log
            me.productos = response.data;
            console.log('Productos:', me.productos); // Agrega este log
        })
},

      showDialog() {
        this.inventario = {
        id: null,
        cantidad_inventario: "",
        fecha_agregado: "",
        fecha_vencimiento: "",
        producto: null,
        producto_id:null
    };
    this.inventarioErrors = {
        cantidad_inventario: false,
        fecha_agregado: false,
        fecha_vencimiento: false,
        producto: false,
    };
    $('#inventarioModal').modal('show');
},
        showDialogEditar(inventario) {
      let me = this;
    $('#inventarioModal').modal('show');    
        me.editedInventario = me.inventarios.indexOf(inventario);
        me.inventario = Object.assign({}, inventario);
},
        hideDialog() {
            let me = this;
            setTimeout(() => {
                me.inventario = {
                id: null,
                cantidad_inventario:0,
                fecha_agregado: new Date,
                fecha_vencimiento: new Date,
                producto:null
                }
            }, 300);
            $('#inventarioModal').modal('hide');
        },
        async saveOrUpdate() {
            let me = this;
         
            me.inventario.cantidad_inventario == '' ? me.inventarioErrors.cantidad_inventario = true : me.inventarioErrors.cantidad_inventario = false;
            me.inventario.producto_id == null ? me.inventarioErrors.producto = true : me.inventarioErrors.producto = false;
            me.inventario.fecha_agregado == '' ? me.inventarioErrors.fecha_agregado = true : me.inventarioErrors.fecha_agregado  = false;
            me.inventario.fecha_vencimiento == '' ? me.inventarioErrors.fecha_vencimiento = true : me.inventarioErrors.fecha_vencimiento = false;

                if (me.inventario.producto_id && me.inventario.fecha_agregado && me.inventario.fecha_vencimiento) {
        if (me.validarDuplicado()) {
            me.$swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El producto con la misma fecha de agregado y fecha de vencimiento ya existe.'
            });
            return;
        }

        let accion = me.inventario.id == null ? "add" : "upd";

        let formData = new FormData();

        formData.append("producto_id", me.inventario.producto_id);
        formData.append("cantidad_inventario", me.inventario.cantidad_inventario);
        formData.append("fecha_agregado", me.inventario.fecha_agregado);
        formData.append("fecha_vencimiento", me.inventario.fecha_vencimiento);

                //definiendo encabezado de peticion
                let headers = {
                  headers: {
                      "Content-Type": "multipart/form-data"
                  }
                };
                if (accion == "add") {

                    //peticion para guardar una auto
                    await this.axios.post('/inventarios', formData, headers) 
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
                     formData.append("id", this.inventario.id);
                   await this.axios
                   .post(`/inventarios/${me.inventario.id}`, formData, headers)
                    .then(response => {
                            if(response.status == 201){
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
        validarDuplicado() {
    // Verificar si el producto con la misma cantidad, fecha de agregado y fecha de vencimiento ya existe
    return this.inventarios.some(item =>
        item.producto_id == this.inventario.producto_id &&
        item.fecha_agregado == this.inventario.fecha_agregado &&
        item.fecha_vencimiento == this.inventario.fecha_vencimiento
    );
},
        async eliminar(inventario) {
            let me = this;
            this.$swal.fire({
                title: 'Seguro de eliminar este registro?',
                text: "No podrás revertir esta accion",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.value) {
                    me.editedInventario = me.inventarios.indexOf(inventario);
                    this.axios.delete(`/inventarios/${inventario.id}`)
                        .then(response => {
                            me.verificarAccion(null, response.status, "del");
                        }).catch(errors => {
                            console.log(errors);
                        })
                }
            })
        },
        verificarAccion(inventario, statusCode, accion) {
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
                    //agregamos al principio del arreglo producto, la nueva producto
                    //me.auto.unshift(auto);
                    me.fetchInventarios();
                    Toast.fire({
                        icon: 'success',
                        'title': 'Inventario registrado con exito...!'
                    });
                    break;
                case "upd":
                    Object.assign(me.inventarios[me.editedInventario], inventario);
                    Toast.fire({
                        icon: 'success',
                        'title': 'Inventario actualizada con exito...!'
                    });
                    me.hideDialog();
                    break;

                case "del":
                    if (statusCode == 200) {
                        try{
                        me.inventarios.splice(this.editedInventario, 1);
                        Toast.fire({
                            icon: 'success',
                            'title': 'Producto eliminada con exito...!'
                        });
                        }catch(error){
                            console.log(error);
                        }
                    } else {
                        Toast.fire({
                            icon: 'warning',
                            'title': 'Ocurrió un error, intente de nuevo...!'
                        });
                    }
                    break;
            }
        },
    },
    mounted() {
        this.fetchInventarios();
        this.fetchProductos();
    }
  }
  </script>