import Tool from './pages/Tool'
import Orders from './pages/Orders'
import BusVehiclesLocations from './pages/BusVehiclesLocations'

Nova.booting((app, store) => {
  Nova.inertia('Bus', Tool)
  Nova.inertia('BusOrders', Orders)
  Nova.inertia('BusVehiclesLocations', BusVehiclesLocations)
})
