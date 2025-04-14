#!/bin/bash

# Get the tenant ID from the argument
TENANT_ID=${1:-elijah-83577}

# Log what we're doing
echo "Fixing theme CSS for tenant: $TENANT_ID"

# Create the CSS content
CSS=$(cat << 'EOF'
:root {
  --color-primary: #3B82F6;
  --color-button-bg: #3B82F6;
  --color-button-text: #FFFFFF;
  --color-footer-bg: #1F2937;
  --color-navbar-text: #111827;
  --color-cart-badge: #EF4444;
  --color-body-bg: #F9FAFB;
  --color-link: #2563EB;
  --color-card-bg: #FFFFFF;
  --font-family: Inter, sans-serif;
  --border-radius: 0.375rem;
}

html, body {
  background-color: var(--color-body-bg);
  font-family: var(--font-family);
}

.btn-primary {
  background-color: var(--color-button-bg);
  color: var(--color-button-text);
}

a {
  color: var(--color-link);
}

.card {
  background-color: var(--color-card-bg);
  border-radius: var(--border-radius);
}

header .navbar {
  color: var(--color-navbar-text);
}

footer {
  background-color: var(--color-footer-bg);
}

.cart-count {
  background-color: var(--color-cart-badge);
}

/* Custom CSS - Add your custom styles below */
EOF
)

# Define the paths
TARGET_DIR="storage/tenant${TENANT_ID}/app/public/tenant-${TENANT_ID}/theme"
TARGET_FILE="${TARGET_DIR}/theme.css"

# Create the directory if it doesn't exist
mkdir -p "$TARGET_DIR"
echo "Created directory: $TARGET_DIR"

# Write the CSS to the file
echo "$CSS" > "$TARGET_FILE"
echo "Created CSS file: $TARGET_FILE"

# Make sure it's readable
chmod 644 "$TARGET_FILE"
echo "Set permissions on CSS file"

# Check that the file was created
if [[ -f "$TARGET_FILE" ]]; then
  echo "SUCCESS: CSS file created at $TARGET_FILE"
  echo "Content length: $(wc -c < "$TARGET_FILE") bytes"
else
  echo "ERROR: Failed to create CSS file at $TARGET_FILE"
fi 