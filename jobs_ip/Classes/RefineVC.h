#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "CXMLDocument.h"

@interface RefineVC : VCBase <UITableViewDelegate, UITableViewDataSource> {
	
	IBOutlet UISearchBar* keywordSearchBar;
	IBOutlet UISearchBar* locationSearchBar;
	CXMLDocument* doc;
	IBOutlet UITableView* tableView;
	IBOutlet UIPickerView* pickerView;
	NSInteger selectedIndex;
	NSMutableDictionary* refineParams;
}

@property (nonatomic,retain) IBOutlet UISearchBar* keywordSearchBar;
@property (nonatomic,retain) IBOutlet UISearchBar* locationSearchBar;
@property (nonatomic,retain) CXMLDocument* doc;
@property (nonatomic,retain) IBOutlet UITableView* tableView;
@property (nonatomic,retain) IBOutlet UIPickerView* pickerView;

@end
