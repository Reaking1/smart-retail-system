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
    @JoinColumn(name = "order_id", nullable = false)
    private Order order;

    // Constructors
    public Payment() {}

    public Payment(BigDecimal amount, String method, String status, LocalDateTime createdAt, Order order) {
        this.amount = amount;
        this.method = method;
        this.status = status;
        this.createdAt = createdAt;
        this.order = order;
    }

    // Getters
    public Long getPaymentId() {
        return paymentId;
    }

    public BigDecimal getAmount() {
        return amount;
    }

    public String getMethod() {
        return method;
    }

    public String getStatus() {
        return status;
    }

    public LocalDateTime getCreatedAt() {
        return createdAt;
    }

    public Order getOrder() {
        return order;
    }

    // Setters
    public void setPaymentId(Long paymentId) {
        this.paymentId = paymentId;
    }

    public void setAmount(BigDecimal amount) {
        this.amount = amount;
    }

    public void setMethod(String method) {
        this.method = method;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public void setCreatedAt(LocalDateTime createdAt) {
        this.createdAt = createdAt;
    }

    public void setOrder(Order order) {
        this.order = order;
    }
}
