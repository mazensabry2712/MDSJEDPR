$response = Invoke-WebRequest -Uri "http://mdsjedpr.test/reports?filter[pr_number]=1" -UseBasicParsing
$content = $response.Content

# Extract project rows from table
if ($content -match '(?s)<tbody>(.*?)</tbody>') {
    $tbody = $matches[1]

    # Count how many tr tags (rows)
    $rows = ([regex]::Matches($tbody, '<tr')).Count

    Write-Host "=== Filter Test: pr_number=1 ===" -ForegroundColor Green
    Write-Host "Number of rows returned: $rows" -ForegroundColor Yellow

    # Extract PR numbers from the tbody
    if ($tbody -match 'PR-(\d+)') {
        Write-Host "`nPR Numbers found in results:" -ForegroundColor Cyan
        [regex]::Matches($tbody, 'PR-(\d+)') | ForEach-Object {
            Write-Host "  - PR-$($_.Groups[1].Value)" -ForegroundColor White
        }
    }
} else {
    Write-Host "Could not extract table body" -ForegroundColor Red
}

Write-Host "`n=== Test Complete ===" -ForegroundColor Green
