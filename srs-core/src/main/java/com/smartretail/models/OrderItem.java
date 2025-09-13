package com.smartretail.models;

import jakarta.persistence.*;
import java.math.BigDecimal;

@Entity
@Table(name = "order_items")
public class OrderItem {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long orderItemId;

    private int quantity;
    private BigDecimal price;

    // Belongs to an order
    @ManyToOne
    @JoinColumn(name = "order_id")
    private Order order;

    // Refers to a product
    @ManyToOne
    @JoinColumn(name = "product_id")
    private Product product;

    // Getters and setters
    // ...
}
