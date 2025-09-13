package com.smartretail.repositores;

import com.smartretail.models.OrderItem;
import com.smartretail.models.Order;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.List;

@Repository
public interface OrderItemRepository extends JpaRepository<OrderItem, Long> {
    // Example: get all items of an order
    List<OrderItem> findByOrder(Order order);
}
