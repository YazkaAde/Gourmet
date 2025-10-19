class RealTimeUpdates {
    constructor() {
        this.isCashier = document.body.classList.contains('cashier-dashboard');
        this.isCustomer = document.body.classList.contains('customer-dashboard');
        this.pollingInterval = null;
        this.lastUpdate = null;
        this.updateInterval = 5000;
    }

    init() {
        if (this.isCashier) {
            this.startCashierPolling();
        } else if (this.isCustomer) {
            this.startCustomerPolling();
        }
    }

    startCashierPolling() {
        this.pollingInterval = setInterval(() => {
            this.updateCashierDashboard();
            this.updateOrdersCount();
            this.updatePaymentsCount();
            this.updateReservationsCount();
        }, this.updateInterval);
    }

    async updateCashierDashboard() {
        try {
            const response = await fetch('/api/cashier/dashboard-stats');
            const data = await response.json();
            
            this.updateDashboardCards(data);
            this.showUpdateNotification();
        } catch (error) {
            console.error('Error updating dashboard:', error);
        }
    }

    updateDashboardCards(data) {
        // Update Today's Orders
        const todayOrdersElement = document.querySelector('[data-card="today-orders"]');
        if (todayOrdersElement) {
            todayOrdersElement.textContent = data.todayOrders || 0;
        }

        // Update Today's Revenue
        const todayRevenueElement = document.querySelector('[data-card="today-revenue"]');
        if (todayRevenueElement) {
            todayRevenueElement.textContent = this.formatCurrency(data.todayRevenue || 0);
        }

        // Update Pending Payments
        const pendingPaymentsElement = document.querySelector('[data-card="pending-payments"]');
        if (pendingPaymentsElement) {
            pendingPaymentsElement.textContent = data.pendingPayments || 0;
        }

        // Update Today's Reservations
        const todayReservationsElement = document.querySelector('[data-card="today-reservations"]');
        if (todayReservationsElement) {
            todayReservationsElement.textContent = data.todayReservations || 0;
        }

        // Update Quick Action counts
        this.updateQuickActions(data);
    }

    updateQuickActions(data) {
        // Update pending orders count in quick actions
        const pendingOrdersBadge = document.querySelector('[data-badge="pending-orders"]');
        if (pendingOrdersBadge) {
            pendingOrdersBadge.textContent = data.pendingOrders || 0;
        }

        // Update processing orders count
        const processingOrdersBadge = document.querySelector('[data-badge="processing-orders"]');
        if (processingOrdersBadge) {
            processingOrdersBadge.textContent = data.processingOrders || 0;
        }

        // Update pending payments count
        const pendingPaymentsBadge = document.querySelector('[data-badge="pending-payments"]');
        if (pendingPaymentsBadge) {
            pendingPaymentsBadge.textContent = data.pendingPayments || 0;
        }

        // Update paid payments count
        const paidPaymentsBadge = document.querySelector('[data-badge="paid-payments"]');
        if (paidPaymentsBadge) {
            paidPaymentsBadge.textContent = data.paidPayments || 0;
        }

        // Update reservations counts
        const pendingReservationsBadge = document.querySelector('[data-badge="pending-reservations"]');
        if (pendingReservationsBadge) {
            pendingReservationsBadge.textContent = data.pendingReservations || 0;
        }

        const confirmedReservationsBadge = document.querySelector('[data-badge="confirmed-reservations"]');
        if (confirmedReservationsBadge) {
            confirmedReservationsBadge.textContent = data.confirmedReservations || 0;
        }
    }

    async updateOrdersCount() {
        try {
            const response = await fetch('/api/cashier/orders-count');
            const data = await response.json();
            this.updateRecentOrders(data.recentOrders);
        } catch (error) {
            console.error('Error updating orders count:', error);
        }
    }

    async updatePaymentsCount() {
        try {
            const response = await fetch('/api/cashier/payments-count');
            const data = await response.json();
            this.updateRecentPayments(data.recentPayments);
        } catch (error) {
            console.error('Error updating payments count:', error);
        }
    }

    async updateReservationsCount() {
        try {
            const response = await fetch('/api/cashier/reservations-count');
            const data = await response.json();
            // Update reservations data if needed
        } catch (error) {
            console.error('Error updating reservations count:', error);
        }
    }

    updateRecentOrders(orders) {
        const ordersContainer = document.querySelector('[data-container="recent-orders"]');
        if (!ordersContainer || !orders) return;

        if (orders.length === 0) {
            ordersContainer.innerHTML = this.getEmptyState('No recent orders');
            return;
        }

        ordersContainer.innerHTML = orders.map(order => `
            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Order #${order.id}</p>
                        <p class="text-sm text-gray-600">${order.customer} • Table ${order.table}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">${this.formatCurrency(order.amount)}</p>
                    <span class="px-2 py-1 text-xs rounded-full ${this.getStatusClass(order.status)}">
                        ${this.capitalizeFirst(order.status)}
                    </span>
                </div>
            </div>
        `).join('');
    }

    updateRecentPayments(payments) {
        const paymentsContainer = document.querySelector('[data-container="recent-payments"]');
        if (!paymentsContainer || !payments) return;

        if (payments.length === 0) {
            paymentsContainer.innerHTML = this.getEmptyState('No pending payments');
            return;
        }

        paymentsContainer.innerHTML = payments.map(payment => `
            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Payment #${payment.id}</p>
                        <p class="text-sm text-gray-600">${payment.customer} • ${payment.type}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">${this.formatCurrency(payment.amount)}</p>
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                        ${this.capitalizeFirst(payment.method)}
                    </span>
                </div>
            </div>
        `).join('');
    }

    // Customer Polling
    startCustomerPolling() {
        this.pollingInterval = setInterval(() => {
            this.updateCustomerOrders();
            this.updateCustomerReservations();
        }, this.updateInterval);
    }

    async updateCustomerOrders() {
        try {
            const response = await fetch('/api/customer/orders-updates');
            const data = await response.json();
            this.updateOrdersList(data.orders);
        } catch (error) {
            console.error('Error updating customer orders:', error);
        }
    }

    async updateCustomerReservations() {
        try {
            const response = await fetch('/api/customer/reservations-updates');
            const data = await response.json();
            this.updateReservationsList(data.reservations);
        } catch (error) {
            console.error('Error updating customer reservations:', error);
        }
    }

    updateOrdersList(orders) {
        // Implement order list updates for customer
        // This will update the orders index page
    }

    updateReservationsList(reservations) {
        // Implement reservations list updates for customer
        // This will update the reservations index page
    }

    // Utility methods
    formatCurrency(amount) {
        return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
    }

    capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    getStatusClass(status) {
        const classes = {
            'completed': 'bg-green-100 text-green-800',
            'processing': 'bg-yellow-100 text-yellow-800',
            'pending': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    getEmptyState(message) {
        return `
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">${message}</p>
            </div>
        `;
    }

    showUpdateNotification() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit',
            second: '2-digit'
        });
        
        console.log(`Dashboard updated at ${timeString}`);
    }

    destroy() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.realTimeUpdates = new RealTimeUpdates();
    window.realTimeUpdates.init();
});