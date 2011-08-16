#import <UIKit/UIKit.h>

@interface LatestVideosByCategoryTC : UITableViewController {
	NSArray* dataArr;
	NSArray* originalDataArr;
	UINavigationController* navCntrl;
}

@property (nonatomic, retain) NSArray* dataArr;
@property (nonatomic, retain) NSArray* originalDataArr;
@property (nonatomic, retain) UINavigationController* navCntrl;

@end
