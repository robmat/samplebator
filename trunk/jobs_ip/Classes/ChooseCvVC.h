#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "CXMLDocument.h"
#import "ASIHTTPRequestDelegate.h"

@interface ChooseCvTVC : UITableViewController <ASIHTTPRequestDelegate> {
	
	UINavigationController* navCntrl;
	CXMLDocument* doc;
	int cvId;
	id parentVC;
}

@property (nonatomic, retain) UINavigationController* navCntrl;
@property (nonatomic, retain) CXMLDocument* doc;
@property (nonatomic, retain) id parentVC;

@end

@interface ChooseCvVC : VCBase {
	NSString* jobId;
	IBOutlet UILabel* jobTitleLbl;
	ChooseCvTVC* tableVC;
	IBOutlet UITableView* tableView;
	IBOutlet UIButton* nextBtn;
}

@property(nonatomic, retain) NSString* jobId;
@property(nonatomic, retain) IBOutlet UILabel* jobTitleLbl;
@property(nonatomic, retain) IBOutlet UITableView* tableView;
@property(nonatomic, retain) IBOutlet UIButton* nextBtn;

- (void)nextAction: (id) sender;
- (void)enableNext;

@end

@interface ChooseCvTC : UITableViewCell {
	
	IBOutlet UILabel* titlLbl;
	IBOutlet UILabel* dateLbl;
	IBOutlet UILabel* descLbl;
	IBOutlet UIImageView* imageV;
}

@property(nonatomic,retain) IBOutlet UILabel* titlLbl;
@property(nonatomic,retain) IBOutlet UILabel* dateLbl;
@property(nonatomic,retain) IBOutlet UILabel* descLbl;
@property(nonatomic,retain) IBOutlet UIImageView* imageV;

@end