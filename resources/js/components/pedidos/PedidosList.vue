<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ listTitle }}</h3>
                        <div class="form-group">
                            <input type="text" v-model="searchCorrelativo" placeholder="Correlativo">
                            <input type="text" v-model="searchFechaEntrega" placeholder="Fecha de Entrega">
                            <input type="text" v-model="searchNombreUsuario" placeholder="Nombre de Usuario">
                            <button @click="fetchPedidos">Buscar</button> 
                      </div>
                      <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="radio"
      id="expressRadio"
      value="express"
      v-model="selectedOption"
      @change="fetchPedidos"
    />
    <label class="form-check-label" for="expressRadio">Pedidos Express</label>
  </div>
  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="radio"
      id="agendadosRadio"
      value="agendados"
      v-model="selectedOption"
      @change="fetchPedidos"
    />
    <label class="form-check-label" for="agendadosRadio">Pedidos Agendados</label>
  </div>
  <div class="form-check form-check-inline">
    <input
      class="form-check-input"
      type="radio"
      id="realizadosRadio"
      value="realizados"
      v-model="selectedOption"
      @change="fetchPedidos"
    />
    <label class="form-check-label" for="agendadosRadio" >Pedidos Realizados</label>
                         <div v-if="selectedOption === 'realizados'" class="ml-5">
                            <!-- Botón adicional para Pedidos Realizados -->
                           
                        </div>
                     </div>
                </div>
  
                   <div class="card-body">
                         <table class="table table-bordered">
                            <thead>
                                <th>Correlativo</th>
                                <th>Direccion Envio</th>
                                <th>Fecha Entrega</th>
                                <th>Hora entrega</th>
                                <th>Telefono</th>                         
                                <th>Cliente</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                                <tr v-for="item in pedidos" :key="item.id" :class="getFechaEntregaColor(item.fecha_entrega, item.estado)">
                                    <td>{{ item.correlativo }}</td>
                                    <td>{{ item.direccion_envio }}</td>
                                    <td>{{ item.fecha_entrega }}</td>
                                    <td>{{ item.hora }}</td>
                                    <td>{{ item.telefono }}</td>
                                    <td>{{ item.user.name }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" @click="showDialog(item)">Ver detalle</button>
                                        &nbsp;
                                        <!---<button v-if="item.estado !== 'E'" class="btn btn-success btn-sm" @click="cambiarEstadoPedido(item.id, 'E')">Express</button>
                                        &nbsp;
                                        <button v-if="item.estado !== 'A'" class="btn btn-info btn-sm" @click="cambiarEstadoPedido(item.id, 'A')">Agendados</button>-->
                                         <button v-if="item.estado !== 'P'" class="btn btn-primary btn-sm" @click="cambiarEstadoPedido(item.id, 'P')">Pedido Realizado</button>
                                        &nbsp;
                                        <button v-if="item.estado !== 'P' && selectedOption === 'express'" class="btn btn-danger btn-sm" @click="cancelarPedido(item.id, 'C')">Anular Pedido</button>
                                        <button v-if="item.estado !== 'P' && selectedOption === 'agendados'" class="btn btn-danger btn-sm" @click="cancelarPedido(item.id, 'C')">Anular Pedido</button>
                                    </td>
                                </tr>
                        </tbody>
                </table>
             </div>
          </div>
       </div>
    </div>
  </div>
  <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="detalleModalLabel">Detalle del Pedido</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- Definiendo el cuerpo del modal-->
            <div class="row">
                <div class="form-group col-12">
                    <div class="col-6">
                        <label for="" v-if="pedido !=null">N° Pedido:&nbsp;{{ pedido.correlativo }}</label>
                    </div>
                        <div class="col-6">
                            <label for="" v-if="pedido !=null">Cliente:&nbsp;{{ pedido.user.name }}</label>
                        </div>
                        <div class="row">
                        <div class="col-6">
                            <label for="" v-if="pedido !=null">Fecha Entrega:&nbsp;{{ pedido.fecha_entrega }}</label>
                        </div>
                        <div class="col-6">
                            <label for="" v-if="pedido !=null">Hora Entrega:&nbsp;{{ pedido.hora }}</label>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <th>Producto</th>
                        <th>Sabor</th>
                        <th>Relleno</th>
                        <th>Catalogo</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Costo Envio</th>
                    </thead>
                    <tbody>
                        <tr v-if="pedido != null" v-for="item in pedido.detallePedidos" :key="item.id">
                            <td>{{ item.producto.nombre }}</td>
                            <td>
                             <span v-for="(sabor, index) in item.producto.sabores" :key="index">
                              {{ sabor.nombre }}
                              <span v-if="index < item.producto.sabores.length - 1">, </span>
                             </span>
                            </td>
                            <td>{{ item.producto.relleno.nombre }}</td>
                            <td>{{ item.producto.catalogo.nombre }}</td>
                            <td>{{ item.cantidad }}</td>
                            <td class="text-center">{{ '$' + pedido.total }}</td>
                            <td class="text-center">{{ '$' + pedido.costo_envio }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
        </div>
    </div>
    </div>
 </template>
  
  <script>
    import Swal from 'sweetalert2';
    export default {
      data() {
          return{
            selectedOption: '',
              mostrarExpress:false,
              mostrarAgendados:false,
              mostrarRealizados:false,
              searchCorrelativo: '',
              searchFechaEntrega:'',
              searchNombreUsuario:'',
              listTitle:"Gestion de Reservas de Pedidos",
              pedidos:[],
              pedido: null,
            //PARA GENERAR LOS REPORTES EN PDF
              fechaInicio: null,
                fechaFinal: null,
          }
      },
        watch:{
            searchCorrelativo() {
                this.fetchPedidos();
            },
            searchFechaEntrega(){
                this.fetchPedidos();
            },
            searchNombreUsuario() {
                this.fetchPedidos();
            },
            selectedOption() {
      // Establecer el estado mostrarExpress y mostrarAgendados según la opción seleccionada
      this.mostrarExpress = this.selectedOption === 'express';
      this.mostrarAgendados = this.selectedOption === 'agendados';
      this.mostrarRealizados = this.selectedOption === 'realizados';
      // Llamar a la función fetchPedidos
      this.fetchPedidos();
    },
            mostrarExpress(){
                let me = this;
                if(me.mostrarExpress){
                    me.estado = 'E';
                    me.listTitle = "Gestion de Pedidos Express";
                }
                me.fetchPedidos();
            },
            mostrarAgendados(){
                let me = this;
                if(me.mostrarAgendados){
                    me.estado = 'A';
                    me.listTitle = "Gestion de Pedidos Agendados";
                }
                me.fetchPedidos();
            },
            mostrarRealizados(){
                let me = this;
                if(me.mostrarRealizados){
                    me.estado = 'P';
                    me.listTitle = "Gestion de Pedidos Realizados";
                }
                me.fetchPedidos();
            },
        },
        methods:{
            async fetchPedidos(){
                let me = this;
                await this.axios.get(`/pedidos/${me.estado}`)
                .then(response =>{
                    console.log(response.data);
                    if(Object.keys(response.data).length==0){
                        me.pedidos = [];
                    }else{
                        me.pedidos = response.data.filter(pedido => {
                        const correlativoMatches = pedido.correlativo.toLowerCase().includes(me.searchCorrelativo.toLowerCase());
                        const fechaEntregaMatches = pedido.fecha_entrega.toLowerCase().includes(me.searchFechaEntrega.toLowerCase());
                        const nombreUsuarioMatches = pedido.user.name.toLowerCase().includes(me.searchNombreUsuario.toLowerCase());

                        return correlativoMatches && fechaEntregaMatches && nombreUsuarioMatches;
                    });
                    }
                }).catch(errors=>{
                    console.log(errors);
                });
            },
            getFechaEntregaColor(fechaEntrega, estado) {
    if (estado === 'P') {
        // Pedidos realizados no tienen color
        return '';
    }

    const now = new Date();
    const fechaEntregaDate = new Date(fechaEntrega);

    // Diferencia en milisegundos entre la fecha actual y la fecha de entrega
    const diferenciaTiempo = fechaEntregaDate - now;

    // Número de milisegundos en un día
    const msEnUnDia = 24 * 60 * 60 * 1000;

    if (estado === 'E' && diferenciaTiempo > msEnUnDia * 2) {
        // Más de dos días para la entrega en estado express (verde)
        return 'bg-success';
    } else if (estado === 'A' && diferenciaTiempo > msEnUnDia * 2) {
        // Más de dos días para la entrega en estado agendado (verde)
        return 'bg-success';
    } else if (diferenciaTiempo > 0) {
        // Menos de dos días para la entrega (amarillo)
        return 'bg-warning';
    } else {
        // Entrega hoy o en el pasado (rojo)
        return 'bg-danger';
    }
},
     async verificarPedidosAgendados() {
      const now = new Date();
      const pedidosAgendados = this.pedidos.filter(
        (pedido) => pedido.estado === 'A' && this.parseFechaHora(pedido.fecha_entrega, pedido.hora) < now
      );

      for (const pedido of pedidosAgendados) {
        // Anula automáticamente los pedidos agendados que no se han realizado
        await this.cancelarPedido(pedido.id);
      }
    },

    parseFechaHora(fecha, hora) {
      // Función para convertir la fecha y hora a un objeto Date
      const [year, month, day] = fecha.split('-');
      const [hour, minute] = hora.split(':');
      return new Date(year, month - 1, day, hour, minute);
    },
           async cambiarEstadoPedido(id, nuevoEstado) {
             try {
                await this.axios.put(`/pedidos/${id}/change`, { nuevoEstado });
                Swal.fire({
                icon: 'success',
                title: `Pedido ha sido cambiado a ${nuevoEstado}`,
                text: 'El pedido se pasó de estado correctamente.',
                confirmButtonText: 'OK'
                })
                .then(() => {
                // Recargar la página
                window.location.reload();
                });

            } catch (error) {
            console.error("Error en el componente al cambiar el estado:", error);
             // Muestra una alerta de error si la cancelación falla
             Swal.fire({
               icon: 'error',
               title: 'Error',
               text: 'Hubo un problema al cambiar el estado del pedido.',
           });
          }
             },
             async cancelarPedido(id) {
    try {
        // Utiliza SweetAlert para mostrar una ventana de confirmación
        const confirmacion = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción anulará el pedido.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, anularlo',
            cancelButtonText: 'Cancelar'
        });

        if (confirmacion.isConfirmed) {
            await this.axios.put(`/pedidos/${id}/cancelar`, { nuevoEstado: 'C' });
            Swal.fire({
                title: 'Éxito',
                text: 'El pedido se eliminó correctamente',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Recargar la página
                window.location.reload();
            });
        }
    } catch (error) {
        console.error("Error al cancelar el pedido:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al eliminar el pedido.',
        });
    }
},
            showDialog(item){
                console.log(item);

                $('#detalleModal').modal('show');
                //me.editedAlquiler = me.alquileres.indexOf(alquiler);
                this.pedido = item;
            }
        },
        mounted(){
            let me = this;
            me.fetchPedidos();
              // Llama al método para verificar pedidos agendados cada minuto (ajusta según tus necesidades)
            setInterval(this.verificarPedidosAgendados, 60000);

            console.log('component mounted')
        }
    }
  </script>