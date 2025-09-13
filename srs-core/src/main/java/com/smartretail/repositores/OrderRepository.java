package com.smartretail.repositores;

import com.smartretail.models.Order;
import com.smartretail.models.Customer;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface OrderRepository extends JpaRepository<Order, Long> {
    // Example: find all orders by customer
    List<Order> findByCustomer(Customer customer);
}
