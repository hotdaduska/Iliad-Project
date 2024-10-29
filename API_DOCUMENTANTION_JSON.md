{
  "openapi": "3.1.0",
  "info": {
    "title": "Laravel",
    "version": "0.0.1"
  },
  "servers": [
    {
      "url": "http://localhost:8000/api"
    }
  ],
  "paths": {
    "/orders": {
      "get": {
        "operationId": "orders.index",
        "tags": [
          "Order"
        ],
        "responses": {
          "200": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          }
        }
      },
      "post": {
        "operationId": "orders.store",
        "tags": [
          "Order"
        ],
        "requestBody": {
          "required": true,
          "content": {
            "application/json": {
              "schema": {
                "type": "object",
                "properties": {
                  "name": {
                    "type": "string",
                    "maxLength": 255
                  },
                  "description": {
                    "type": [
                      "string",
                      "null"
                    ],
                    "maxLength": 255
                  },
                  "order_date": {
                    "type": "string"
                  },
                  "products": {
                    "type": "array",
                    "items": {
                      "type": "object",
                      "properties": {
                        "id": {
                          "type": "integer"
                        },
                        "quantity": {
                          "type": "integer",
                          "minimum": 1
                        }
                      },
                      "required": [
                        "id",
                        "quantity"
                      ]
                    }
                  }
                },
                "required": [
                  "name",
                  "order_date",
                  "products"
                ]
              }
            }
          }
        },
        "responses": {
          "201": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          },
          "422": {
            "$ref": "#/components/responses/ValidationException"
          }
        }
      }
    },
    "/orders/{id}": {
      "get": {
        "operationId": "orders.show",
        "tags": [
          "Order"
        ],
        "parameters": [
          {
            "name": "id",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "responses": {
          "200": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          }
        }
      },
      "put": {
        "operationId": "orders.update",
        "tags": [
          "Order"
        ],
        "parameters": [
          {
            "name": "id",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "responses": {
          "200": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "object",
                  "properties": {
                    "message": {
                      "type": "string",
                      "example": "Order updated successfully."
                    }
                  },
                  "required": [
                    "message"
                  ]
                }
              }
            }
          }
        }
      },
      "delete": {
        "operationId": "orders.destroy",
        "tags": [
          "Order"
        ],
        "parameters": [
          {
            "name": "id",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "responses": {
          "204": {
            "description": "No content",
            "content": {
              "application/json": {
                "schema": {
                  "type": "null"
                }
              }
            }
          }
        }
      }
    },
    "/order-products": {
      "get": {
        "operationId": "order-products.index",
        "tags": [
          "OrderProduct"
        ],
        "responses": {
          "200": {
            "description": ""
          }
        }
      },
      "post": {
        "operationId": "order-products.store",
        "tags": [
          "OrderProduct"
        ],
        "requestBody": {
          "required": true,
          "content": {
            "application/json": {
              "schema": {
                "type": "object",
                "properties": {
                  "order_id": {
                    "type": "integer"
                  },
                  "product_id": {
                    "type": "integer"
                  },
                  "quantity": {
                    "type": "integer",
                    "minimum": 1
                  }
                },
                "required": [
                  "order_id",
                  "product_id",
                  "quantity"
                ]
              }
            }
          }
        },
        "responses": {
          "201": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          },
          "422": {
            "$ref": "#/components/responses/ValidationException"
          }
        }
      }
    },
    "/order-products/{id}": {
      "get": {
        "operationId": "order-products.show",
        "tags": [
          "OrderProduct"
        ],
        "parameters": [
          {
            "name": "id",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "responses": {
          "200": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          }
        }
      },
      "put": {
        "operationId": "order-products.update",
        "tags": [
          "OrderProduct"
        ],
        "parameters": [
          {
            "name": "id",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "requestBody": {
          "required": true,
          "content": {
            "application/json": {
              "schema": {
                "type": "object",
                "properties": {
                  "name": {
                    "type": "string"
                  },
                  "description": {
                    "type": [
                      "string",
                      "null"
                    ]
                  },
                  "order_date": {
                    "type": "string"
                  },
                  "products": {
                    "type": "array",
                    "items": {
                      "type": "string"
                    }
                  },
                  "removedProducts": {
                    "type": "array",
                    "items": {
                      "type": "string"
                    }
                  }
                },
                "required": [
                  "name",
                  "order_date"
                ]
              }
            }
          }
        },
        "responses": {
          "200": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "object",
                  "properties": {
                    "message": {
                      "type": "string",
                      "example": "Order updated successfully!"
                    }
                  },
                  "required": [
                    "message"
                  ]
                }
              }
            }
          },
          "422": {
            "$ref": "#/components/responses/ValidationException"
          }
        }
      }
    },
    "/order-products/{order_product}": {
      "delete": {
        "operationId": "order-products.destroy",
        "tags": [
          "OrderProduct"
        ],
        "parameters": [
          {
            "name": "order_product",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "responses": {
          "204": {
            "description": "No content",
            "content": {
              "application/json": {
                "schema": {
                  "type": "null"
                }
              }
            }
          },
          "404": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "object",
                  "properties": {
                    "message": {
                      "type": "string",
                      "example": "Order-Product relationship not found"
                    }
                  },
                  "required": [
                    "message"
                  ]
                }
              }
            }
          }
        }
      }
    },
    "/orders/{order}/products": {
      "get": {
        "operationId": "orderProduct.index_0",
        "tags": [
          "OrderProduct"
        ],
        "parameters": [
          {
            "name": "order",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "responses": {
          "200": {
            "description": ""
          }
        }
      },
      "post": {
        "operationId": "orderProduct.store_1",
        "tags": [
          "OrderProduct"
        ],
        "parameters": [
          {
            "name": "order",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "requestBody": {
          "required": true,
          "content": {
            "application/json": {
              "schema": {
                "type": "object",
                "properties": {
                  "order_id": {
                    "type": "integer"
                  },
                  "product_id": {
                    "type": "integer"
                  },
                  "quantity": {
                    "type": "integer",
                    "minimum": 1
                  }
                },
                "required": [
                  "order_id",
                  "product_id",
                  "quantity"
                ]
              }
            }
          }
        },
        "responses": {
          "201": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          },
          "422": {
            "$ref": "#/components/responses/ValidationException"
          }
        }
      }
    },
    "/orders/{orderId}/associate-products": {
      "post": {
        "operationId": "orderProduct.associateProductsToOrder",
        "tags": [
          "OrderProduct"
        ],
        "parameters": [
          {
            "name": "orderId",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "requestBody": {
          "required": true,
          "content": {
            "application/json": {
              "schema": {
                "type": "object",
                "properties": {
                  "products": {
                    "type": "array",
                    "items": {
                      "type": "object",
                      "properties": {
                        "product_id": {
                          "type": "integer"
                        },
                        "quantity": {
                          "type": "integer",
                          "minimum": 1
                        }
                      },
                      "required": [
                        "product_id",
                        "quantity"
                      ]
                    }
                  }
                },
                "required": [
                  "products"
                ]
              }
            }
          }
        },
        "responses": {
          "201": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "object",
                  "properties": {
                    "message": {
                      "type": "string",
                      "example": "Products associated with order successfully."
                    }
                  },
                  "required": [
                    "message"
                  ]
                }
              }
            }
          },
          "422": {
            "$ref": "#/components/responses/ValidationException"
          }
        }
      }
    },
    "/orders/{order}/products/{product}": {
      "put": {
        "operationId": "orderProduct.update_0",
        "tags": [
          "OrderProduct"
        ],
        "parameters": [
          {
            "name": "order",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          },
          {
            "name": "product",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "requestBody": {
          "required": true,
          "content": {
            "application/json": {
              "schema": {
                "type": "object",
                "properties": {
                  "name": {
                    "type": "string"
                  },
                  "description": {
                    "type": [
                      "string",
                      "null"
                    ]
                  },
                  "order_date": {
                    "type": "string"
                  },
                  "products": {
                    "type": "array",
                    "items": {
                      "type": "string"
                    }
                  },
                  "removedProducts": {
                    "type": "array",
                    "items": {
                      "type": "string"
                    }
                  }
                },
                "required": [
                  "name",
                  "order_date"
                ]
              }
            }
          }
        },
        "responses": {
          "200": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "object",
                  "properties": {
                    "message": {
                      "type": "string",
                      "example": "Order updated successfully!"
                    }
                  },
                  "required": [
                    "message"
                  ]
                }
              }
            }
          },
          "422": {
            "$ref": "#/components/responses/ValidationException"
          }
        }
      }
    },
    "/orders/{orderId}/products/{productId}": {
      "delete": {
        "operationId": "orderProduct.destroy_0",
        "tags": [
          "OrderProduct"
        ],
        "parameters": [
          {
            "name": "orderId",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          },
          {
            "name": "productId",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "responses": {
          "204": {
            "description": "No content",
            "content": {
              "application/json": {
                "schema": {
                  "type": "null"
                }
              }
            }
          },
          "404": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "object",
                  "properties": {
                    "message": {
                      "type": "string",
                      "example": "Order-Product relationship not found"
                    }
                  },
                  "required": [
                    "message"
                  ]
                }
              }
            }
          }
        }
      }
    },
    "/products": {
      "get": {
        "operationId": "products.index",
        "tags": [
          "Product"
        ],
        "responses": {
          "200": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          }
        }
      },
      "post": {
        "operationId": "products.store",
        "tags": [
          "Product"
        ],
        "requestBody": {
          "required": true,
          "content": {
            "application/json": {
              "schema": {
                "type": "object",
                "properties": {
                  "name": {
                    "type": "string",
                    "maxLength": 255
                  },
                  "price": {
                    "type": "number",
                    "minimum": 0
                  }
                },
                "required": [
                  "name",
                  "price"
                ]
              }
            }
          }
        },
        "responses": {
          "201": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          },
          "422": {
            "$ref": "#/components/responses/ValidationException"
          }
        }
      }
    },
    "/products/{id}": {
      "get": {
        "operationId": "products.show",
        "tags": [
          "Product"
        ],
        "parameters": [
          {
            "name": "id",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "responses": {
          "200": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          }
        }
      },
      "put": {
        "operationId": "products.update",
        "tags": [
          "Product"
        ],
        "parameters": [
          {
            "name": "id",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "requestBody": {
          "content": {
            "application/json": {
              "schema": {
                "type": "object",
                "properties": {
                  "name": {
                    "type": "string",
                    "maxLength": 255
                  },
                  "price": {
                    "type": "number",
                    "minimum": 0
                  }
                }
              }
            }
          }
        },
        "responses": {
          "200": {
            "description": "",
            "content": {
              "application/json": {
                "schema": {
                  "type": "string"
                }
              }
            }
          },
          "422": {
            "$ref": "#/components/responses/ValidationException"
          }
        }
      },
      "delete": {
        "operationId": "products.destroy",
        "tags": [
          "Product"
        ],
        "parameters": [
          {
            "name": "id",
            "in": "path",
            "required": true,
            "schema": {
              "type": "string"
            }
          }
        ],
        "responses": {
          "204": {
            "description": "No content",
            "content": {
              "application/json": {
                "schema": {
                  "type": "null"
                }
              }
            }
          }
        }
      }
    }
  },
  "components": {
    "responses": {
      "ValidationException": {
        "description": "Validation error",
        "content": {
          "application/json": {
            "schema": {
              "type": "object",
              "properties": {
                "message": {
                  "type": "string",
                  "description": "Errors overview."
                },
                "errors": {
                  "type": "object",
                  "description": "A detailed description of each field that failed validation.",
                  "additionalProperties": {
                    "type": "array",
                    "items": {
                      "type": "string"
                    }
                  }
                }
              },
              "required": [
                "message",
                "errors"
              ]
            }
          }
        }
      }
    }
  }
}
