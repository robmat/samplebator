#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "GData.h"
#import "LatestVideosByCategoryTC.h"

@interface LatestVideosByCategoryVC : VCBase {
	NSString* category;
	GDataServiceGoogleYouTube* ytService;
	IBOutlet UITableView* tableView;
	LatestVideosByCategoryTC* tableVC;
}

@property (nonatomic, retain) NSString* category;
@property (nonatomic, retain) GDataServiceGoogleYouTube* ytService;
@property (nonatomic, retain) IBOutlet UITableView* tableView;
@property (nonatomic, retain) LatestVideosByCategoryTC* tableVC;

- (void)entryListFetchTicket: (GDataServiceTicket *)ticket finishedWithFeed: (GDataFeedBase *)feed error: (NSError*) error;

@end
