import React, { useState, useEffect } from 'react';

const ProductInput = () => {
    const [productNames, setProductNames] = useState({
        product_codes: [],
        product_colors: [],
        product_sizes: [],
        product_hands: []
    });
    const [product, setProduct] = useState('');
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        fetch('ajax_GET/fetch_product_names.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                setProductNames(data);
                setLoading(false);
            })
            .catch(error => {
                console.error('Error fetching product names:', error);
                setError(error);
                setLoading(false);
            });
    }, []);

    const validateInput = (e) => {
        const value = e.target.value;
        setProduct(value);
    };

    if (loading) return <p>Loading...</p>;
    if (error) return <p>Error loading product names: {error.message}</p>;

    return (
        <td>
            <input
                className="form-control"
                type="text"
                id="product"
                name="product"
                list="product_names"
                value={product}
                onChange={validateInput}
            />
            <datalist id="product_names">
                {productNames.product_codes.map((productName_code, index) => (
                    <option key={`code-${index}`} value={productName_code} />
                ))}
                {productNames.product_colors.map((productName_color, index) => (
                    <option key={`color-${index}`} value={productName_color} />
                ))}
                {productNames.product_sizes.map((productName_size, index) => (
                    <option key={`size-${index}`} value={productName_size} />
                ))}
                {productNames.product_hands.map((productName_hands, index) => (
                    <option key={`hands-${index}`} value={productName_hands} />
                ))}
            </datalist>
        </td>
    );
};

export default ProductInput;
