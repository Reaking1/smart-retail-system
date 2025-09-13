package com.smartretail.models;

import jakarta.persistence.*;
import java.math.BigDecimal;
import java.time.LocalDateTime;

@Entity
@Table(name = "payments")
public class Payment {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long paymentId;

    private BigDecimal amount;
    private String method; // e.g., CREDIT_CARD, PAYPAL
    private String status; // e.g., SUCCESS, FAILED
    private LocalDateTime createdAt;

    // Each payment is linked to one order
    @OneToOne
    @JoinColumn(name = "order_id")
    private Order order;

    // Getters and setters
    // ...
}
