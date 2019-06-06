<?php

namespace App\Service;

class AnalyticsService
{

    public function getDataAndProcess($objectId)
    {
        $analytics = $this->initializeAnalytics();
        $response = $this->getReport($analytics, $objectId);
        return $this->printResults($response);
    }
    /**
     * Initializes an Analytics Reporting API V4 service object.
     *
     * @return An authorized Analytics Reporting API V4 service object.
     */
    public function initializeAnalytics()
    {
    
        // Use the developers console and download your service account
        // credentials in JSON format. Place them in this directory or
        // change the key file location if necessary.
        $KEY_FILE_LOCATION = base_path() . '/api-creds.json';

        // Create and configure a new client object.
        $client = new \Google_Client();
        $client->setApplicationName("Wifi-Statspage");
        $client->setAuthConfig($KEY_FILE_LOCATION);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new \Google_Service_AnalyticsReporting($client);
        return $analytics;
    }
    
    
    /**
     * Queries the Analytics Reporting API V4.
     *
     * @param service An authorized Analytics Reporting API V4 service object.
     * @return The Analytics Reporting API V4 response.
     */
    public function getReport($analytics, $objectId)
    {
    
        // Replace with your view ID, for example XXXX.
        $VIEW_ID = "177388296";
        
        // Create the DateRange object.
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate("30daysAgo");
        $dateRange->setEndDate("today");

        //Create the Dimensions object.
        $browser = new \Google_Service_AnalyticsReporting_Dimension();
        $browser->setName("ga:pagePath");
        $operatingSystem = new \Google_Service_AnalyticsReporting_Dimension();
        $operatingSystem->setName("ga:operatingSystem");

        // Create the Metrics object.
        $sessions = new \Google_Service_AnalyticsReporting_Metric();
        $sessions->setExpression("ga:sessions");
        $sessions->setAlias("sessions");
        $users = new \Google_Service_AnalyticsReporting_Metric();
        $users->setExpression("ga:users");
        $users->setAlias("users");
        
        // Create Dimension Filter.
        $id = "/" . (string)$objectId;
        $dimensionFilter = new \Google_Service_AnalyticsReporting_SegmentDimensionFilter();
        $dimensionFilter->setDimensionName("ga:pagePath");
        $dimensionFilter->setOperator("EXACT");
        $dimensionFilter->setExpressions([$id]);
        
        // Create the DimensionFilterClauses
        $dimensionFilterClause = new \Google_Service_AnalyticsReporting_DimensionFilterClause();
        $dimensionFilterClause->setFilters([$dimensionFilter]);

        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($VIEW_ID);
        $request->setDateRanges($dateRange);
        $request->setMetrics([$sessions,$users]);
        $request->setDimensions([$browser, $operatingSystem]);
        $request->setDimensionFilterClauses([$dimensionFilterClause]);

        // Get new Report
        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests([$request]);
        return $analytics->reports->batchGet($body);
    }

    public function printResults($reports)
    {
            $report = $reports[0];
            $rows = $report->getData()->getRows();
            $data = [];
            for ($rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                $row = $rows[ $rowIndex ];

                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();
                $data[$dimensions[1]] = $metrics[0]->getValues();
            }
            return $data;
    }
}
