#!/bin/bash
set -euo pipefail

# Default: dry run (no deletion)
DRY_RUN=true

if [[ "${1:-}" == "--force" ]]; then
  DRY_RUN=false
fi

# Move to script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

echo "Running in directory: $SCRIPT_DIR"

# Files/folders to keep
EXCEPTIONS=("cleanup.sh" ".env" "perusoikeudet.sh")

# Function to check if item is in exception list
is_exception() {
  local item="$1"
  for exception in "${EXCEPTIONS[@]}"; do
    if [[ "$item" == "$exception" ]]; then
      return 0
    fi
  done
  return 1
}

echo
echo "Scanning directory..."
echo

items_to_delete=()

# Use find for safer iteration
while IFS= read -r -d '' item; do
  name="$(basename "$item")"

  if is_exception "$name"; then
    continue
  fi

  items_to_delete+=("$item")

done < <(find . -mindepth 1 -maxdepth 1 -print0)

# Show what will be deleted
echo "Items that would be deleted:"
for item in "${items_to_delete[@]}"; do
  echo "  $item"
done

echo
echo "Total: ${#items_to_delete[@]} items"

# Dry run mode
if $DRY_RUN; then
  echo
  echo "DRY RUN: Nothing deleted."
  echo "Run with --force to actually delete."
  exit 0
fi

echo
read -p "Type DELETE to confirm: " confirm

if [[ "$confirm" != "DELETE" ]]; then
  echo "Aborted."
  exit 1
fi

# Perform deletion
for item in "${items_to_delete[@]}"; do
  echo "Deleting: $item"
  rm -rf -- "$item"
done

echo
echo "Cleanup complete."
