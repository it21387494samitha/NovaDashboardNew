import Tool from './pages/Tool'
import Orders from './pages/Orders'
import VehiclesLocations from './pages/VehicleLocation'
import BusDetails from './pages/BusDetails'

Nova.booting((app, store) => {
  Nova.inertia('Bus', Tool)
  Nova.inertia('BusOrders', Orders)
  Nova.inertia('BusVehiclesLocations', VehiclesLocations)
  Nova.inertia('BusDetails', BusDetails)
})
