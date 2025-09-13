package com.smartretail.repositores;

import com.smartretail.models.Product;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ProductRepository extends JpaRepository<Product, Long> {
    // Example: find products by name containing keyword
    java.util.List<Product> findByNameContainingIgnoreCase(String keyword);
}
